@extends('master.main')

@section('title', "Edit Uploader: " . $uploader->name)

@php

$s3FormDetails = getS3Details(config('filesystems.disks.s3.bucket'), config('filesystems.disks.s3.region'));


// Get all the necessary details to directly upload a private file to S3
// asynchronously with JavaScript using the Signature V4.
//
// param string $s3Bucket your bucket's name on s3.
// param string $region   the bucket's location/region, see here for details: http://amzn.to/1FtPG6r
// param string $acl      the visibility/permissions of your file, see details: http://amzn.to/18s9Gv7
//
// return array ['url', 'inputs'] the forms url to s3 and any inputs the form will need.
//

function getS3Details($s3Bucket, $region, $acl = 'private') {

// Options and Settings
    $awsKey = config('filesystems.disks.s3.key');
    $awsSecret = config('filesystems.disks.s3.secret');

    $algorithm = "AWS4-HMAC-SHA256";
    $service = "s3";
    $date = gmdate("Ymd\THis\Z");
    $shortDate = gmdate("Ymd");
    $requestType = "aws4_request";
    $expires = "86400"; // 24 Hours
    $successStatus = "201";
    $url = "//{$s3Bucket}.{$service}-{$region}.amazonaws.com";

// Step 1: Generate the Scope
    $scope = [
            $awsKey,
            $shortDate,
            $region,
            $service,
            $requestType
    ];
    $credentials = implode('/', $scope);

// Step 2: Making a Base64 Policy
    $policy = [
            'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+2 hours')),
            'conditions' => [
                    ['bucket' => $s3Bucket],
                    ['acl' => $acl],
                    ['starts-with', '$key', ''],
                    ['starts-with', '$Content-Type', ''],
                    ['success_action_status' => $successStatus],
                    ['x-amz-credential' => $credentials],
                    ['x-amz-algorithm' => $algorithm],
                    ['x-amz-date' => $date],
                    ['x-amz-expires' => $expires],
            ]
    ];
    $base64Policy = base64_encode(json_encode($policy));

// Step 3: Signing your Request (Making a Signature)
    $dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . $awsSecret, true);
    $dateRegionKey = hash_hmac('sha256', $region, $dateKey, true);
    $dateRegionServiceKey = hash_hmac('sha256', $service, $dateRegionKey, true);
    $signingKey = hash_hmac('sha256', $requestType, $dateRegionServiceKey, true);

    $signature = hash_hmac('sha256', $base64Policy, $signingKey);

// Step 4: Build form inputs
// This is the data that will get sent with the form to S3
    $inputs = [
            'Content-Type' => '',
            'acl' => $acl,
            'success_action_status' => $successStatus,
            'policy' => $base64Policy,
            'X-amz-credential' => $credentials,
            'X-amz-algorithm' => $algorithm,
            'X-amz-date' => $date,
            'X-amz-expires' => $expires,
            'X-amz-signature' => $signature
    ];

    return compact('url', 'inputs');
}

@endphp

@section('main')
    <div class="col-sm-12">
        <a href="{{route('dataset.show', [$uploader->dataset_id])}}" class="btn btn-raised btn-primary"><span class="fa fa-angle-left"></span> Back to Data Set</a>
        <button class="btn btn-raised btn-primary pull-right"  data-toggle="modal" data-target="#uploadDialog"><span class="glyphicon glyphicon-upload"></span> New Upload</button>
    </div>
    <div class="col-sm-12">
        <h4>{{$uploader->dataset->name}} > {{$uploader->name}}</h4>
    </div>
    <div class="col-md-12">
        <section class="boxs @if($uploader->uploads->count() == 0) hidden @endif" id="upload_history_box">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Upload History</strong></h1>
            </div>
            <div class="boxs-body">
                <table class="table m-b-0">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Upload Date</th>
                        <th>Process Date</th>
                        <th>Uploaded By</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="upload_history">
                    @php($uploads = $uploader->uploads()->paginate(10))
                    @foreach($uploads as $upload)
                        @include('uploader.types._upload')
                    @endforeach
                    </tbody>
                </table>
                {{ $uploads->links() }}
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <div class="dropdown pull-right">
            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                <i class="zmdi zmdi-more-vert"></i>
            </a>
            <ul class="dropdown-menu" role="menu">
                @unless($uploader->hasSyncClass('address'))<li><a href="{{route('uploader.addressSync', [$uploader->id])}}">Add Address Sync</a></li>@endunless
                <li><a href="{{route('uploader.entitySync', [$uploader->id])}}">Add Entity Sync</a></li>
                {{--<li><a href="{{action('UploaderController@get', ['address-sync', $uploader->id])}}">Add Lot Sync</a></li>--}}
                {{--<li><a href="{{action('UploaderController@get', ['address-sync', $uploader->id])}}">Add Point Sync</a></li>--}}

            </ul>
        </div>
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Data Sync Methods</strong></h1>
            </div>
            <div class="boxs-body">

                <div class="inbox-widget nicescroll" style="height: 315px; overflow: hidden; outline: none;" tabindex="5000">
                    @if($uploader->syncs != null)
                        @foreach($uploader->syncs as $class => $sync)
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="col-xs-2">
                                        @if($sync['class'] == 'address')
                                            <span class="fa fa-building fa-2x inbox-item-img"></span><br>
                                        @elseif($sync['class'] == 'entity')
                                            <span class="fa fa-user fa-2x inbox-item-img"></span><br>
                                        @endif
                                    </div>
                                    <div class="col-xs-10">
                                        @if($sync['class'] == 'address')
                                            <p class="inbox-item-author">{{ucwords($sync['class'])}}</p>
                                            @if(isset($sync['full_address']))
                                                Full Address: <span class="label label-default">{{$sync['full_address']}}</span>
                                            @else
                                                @if(isset($sync['street_number']))Street Number: <span class="label label-default">{{$sync['street_number']}}</span>@endif
                                                @if(isset($sync['street_name']))Street Name: <span class="label label-default">{{$sync['street_name']}}</span>@endif
                                                @if(isset($sync['street_type']))Street Type: <span class="label label-default">{{$sync['street_type']}}</span>@endif
                                                @if(isset($sync['unit']))Unit: <span class="label label-default">{{$sync['unit']}}</span>@endif
                                            @endif
                                            @if(isset($sync['city']))City: <span class="label label-default">{{ $sync['city'] }}</span>@endif
                                            @if(isset($sync['state']))State: <span class="label label-default">{{ $sync['state']}}</span>@endif
                                            @if(isset($sync['postal_code']))Postal Code: <span class="label label-default">{{$sync['postal_code']}}</span>@endif

                                        @elseif($sync['class'] == 'entity')
                                            <p class="inbox-item-author">{{ucwords($sync['class'])}}</p>
                                            @if(isset($sync['full_name']))
                                                Full Name: <span class="label label-default">{{$sync['full_name']}}</span>
                                            @else
                                                Title: <span class="label label-default">{{$sync['title'] ?: 'NULL'}}</span>
                                                First Name: <span class="label label-default">{{$sync['first_name'] ?: 'NULL'}}</span>
                                                Middle Name: <span class="label label-default">{{$sync['middle_name'] ?: 'NULL'}}</span>
                                                Last Name: <span class="label label-default">{{$sync['last_name'] ?: 'NULL'}}</span>
                                                Suffix: <span class="label label-default">{{$sync['suffix'] ?: 'NULL'}}</span>
                                            @endif
                                            Role: <span class="label label-default">{{$sync['role'] ?: 'NULL'}}</span>
                                        @endif

                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            You have no sync settings! Please choose at least one.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Data Filters</strong></h1>
            </div>
            <div class="boxs-body">
                <div class="inbox-widget nicescroll" style="height: 315px; overflow: hidden; outline: none;" tabindex="5000">
                    @if($uploader->filters != null)
                        @foreach($uploader->filters as $key => $filter)
                            <a href="#">
                                <div class="inbox-item">
                                    <p class="inbox-item-author"><i class="label label-default"><span class="fa fa-key"></span> {{$key}}</i></p>

                                    @foreach($filter as $i)
                                        @if($i['type'] == 'searchReplace')
                                            Replace <span class="label label-danger">{{$i['needle']}}</span> with <span class="label label-success">{{$i['replace'] | 'NULL'}}</span>@unless($loop->last); @endunless
                                        @endif
                                    @endforeach
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    </div>



@endsection

@push('modal')
<!-- Modal -->
<div class="modal fade" id="uploadDialog" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Upload from CSV or Excel</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $s3FormDetails['url']; ?>"
                      method="POST"
                      enctype="multipart/form-data"
                      class="direct-upload">
                    <input type="text" class="form-control" id="upload_note" placeholder="Upload description...">
                    <br>
                    <?php foreach ($s3FormDetails['inputs'] as $name => $value) { ?>
                    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
                    <?php } ?>

                    <input type="hidden" name="key" value="">

                    <!-- Key is the file's name on S3 and will be filled in with JS -->
                    <input class="dropify" type="file" name="file">

                    <!-- Progress Bars to show upload completion percentage -->
                    <textarea class="hidden" id="uploaded"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('style')

<link href="/vendor/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" /

@endpush

@push('scripts')
<script src="/vendor/fileuploads/js/dropify.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>

<script>
    // Assigned to variable for later use.
    var form = $('.direct-upload');
    var filesUploaded = [];

    // Place any uploads within the descending folders
    // so ['test1', 'test2'] would become /test1/test2/filename
    var folders = ['{{config('schema')}}', 'data-upload'];

    var size;
    var type;
    var name;

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'error': 'Ooops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big (10M max).'
        }
    });

    form.fileupload({
        url: form.attr('action'),
        type: form.attr('method'),
        datatype: 'xml',
        add: function (event, data) {

            // Show warning message if your leaving the page during an upload.
            window.onbeforeunload = function () {
                return 'You have unsaved changes.';
            };

            // Give the file which is being uploaded it's current content-type (It doesn't retain it otherwise)
            // and give it a unique name (so it won't overwrite anything already on s3).
            var file = data.files[0];
            name = file.name;
            var filename = Date.now() + '.' + name.split('.').pop();
            form.find('input[name="Content-Type"]').val(file.type);
            form.find('input[name="key"]').val((folders.length ? folders.join('/') + '/' : '') + filename);


            name = (folders.length ? folders.join('/') + '/' : '') + filename;
            size = file.size;
            type = file.type;

            // Actually submit to form to S3.
            data.submit();

            // Show the progress bar
            // Uses the file size as a unique identifier
            var bar = $('<div class="progress" data-mod="' + file.size + '"><div class="bar"></div></div>');
            $('.progress-bar-area').append(bar);
            bar.slideDown('fast');
        },
        progress: function (e, data) {
            // This is what makes everything really cool, thanks to that callback
            // you can now update the progress bar based on the upload progress.
            var percent = Math.round((data.loaded / data.total) * 100);
            $('.progress[data-mod="' + data.files[0].size + '"] .bar').css('width', percent + '%').html(percent + '%');
        },
        fail: function (e, data) {
            // Remove the 'unsaved changes' message.
            window.onbeforeunload = null;
            $('.progress[data-mod="' + data.files[0].size + '"] .bar').css('width', '100%').addClass('red').html('');
        },
        done: function (event, data) {

            console.log(event);
            console.log(data);

            window.onbeforeunload = null;

            // Upload Complete, show information about the upload in a textarea
            // from here you can do what you want as the file is on S3
            // e.g. save reference to your server using another ajax call or log it, etc.
            var original = data.files[0];
            var s3Result = data.result.documentElement.children;
            filesUploaded.push(s3Result[0].innerHTML);

            $.ajax({
                url: '{{route('upload.store')}}',
                type: 'POST',
                data: {
                    source: name,
                    size: size,
                    file_type: type,
                    uploader_id: {{$uploader->id}},
                    note: $('#upload_note').val(),
                    slug: 'csv_upload',
                    _token: '{{csrf_token()}}'
                }
            }).success(function (data) {
                $('#upload_history_box').removeClass('hidden');
                $('#upload_history').append(data);
                $('#uploadDialog').modal('hide');
            });
        }
    });
</script>

{{--<script>--}}

{{--var refreshUpload = function(id)--}}
{{--{--}}
{{--var icon = $('#icon-' + id);--}}

{{--icon.addClass('fa-spinner fa-spin').removeClass('fa-navicon');--}}
{{--$.ajax({--}}
{{--url: "{{action('DataStoreController@post', ['process-upload'])}}/" + id,--}}
{{--success: function () {--}}
{{--icon.removeClass('fa-spinner fa-spin').addClass('fa-navicon');--}}
{{--},--}}
{{--fail: function(data) {--}}
{{--console.log(data);--}}
{{--alert('An error has occured in refreshing data. Please see console');--}}
{{--icon.removeClass('fa-spinner fa-spin').addClass('fa-alert');--}}

{{--}--}}
{{--})--}}
{{--}--}}

{{--</script>--}}
@endpush