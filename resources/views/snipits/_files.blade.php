@php

    $s3FormDetails = getS3Details(env('AWS_BUCKET'), env('AWS_REGION'));


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
        $awsKey = (!empty(getenv('AWS_KEY')) ? getenv('AWS_KEY') : AWS_KEY);
        $awsSecret = (!empty(getenv('AWS_SECRET')) ? getenv('AWS_SECRET') : AWS_SECRET);

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
@can('citynexus', ['files', 'upload'])
<div class="btn btn-primary btn-raised" data-toggle="modal" data-target="#addFile"> <i class="fa fa-plus"></i> Add a File</div>
@endcan
<br>
@can('cityneuxs', ['files', 'view'])
    @if($files->count() > 0)
        <div class="list-group">

            @foreach($files->sortBy('caption') as $file)

                <?php
                if($file->current != null)
                {
                    $type = $file->current->type;
                }
                ?>
                @if($type)
                    <div class="list-group-item " id="file_item_{{$file->id}}">
                        <div class="row">
                            <div class="col-xs-11" @if(substr($type, 0, 6) == 'image/') onclick="showImage({{$file->id}})" @else onclick="downloadFile({{$file->id}})" @endif style="cursor: pointer">
                                @if(
                            $type == 'application/pdf' ||
                            $type == 'application/x-pdf'
                            )
                                    <i class="fa fa-file-pdf-o"> </i>
                                @elseif(substr($type, 0, 6) == 'image/')
                                    <i class="fa fa-image"> </i>
                                @elseif($type == 'application/msword')
                                    <i class="fa fa-file-word-o"> </i>
                                @elseif($type == 'application/mspowerpoint')
                                    <i class="fa fa-file-powerpoint-o"> </i>
                                @elseif($type == 'application/msexcel')
                                    <i class="fa fa-file-excel-o"> </i>
                                @else
                                    <i class="fa fa-file"></i>
                                @endif
                                {{$file->caption}} ({{$file->updated_at->diffForHumans()}})
                            </div>
                            <div class="col-xs-1" style="align-content: center">
                                @can('citynexus', ['files', 'delete']) <a href="#" onclick="deleteFile({{$file->id}})"><i class="fa fa-trash"></i> </a> @endcan
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endcan

@push('modal')

<!-- Modal -->
<div class="modal fade" id="addFile" tabindex="-1" role="dialog" aria-labelledby="addFileLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a File</h4>
            </div>
            <div class="modal-body">

                <!-- Direct Upload to S3 Form -->
                <form action="<?php echo $s3FormDetails['url']; ?>"
                      method="POST"
                      enctype="multipart/form-data"
                      class="direct-upload">

                    <?php foreach ($s3FormDetails['inputs'] as $name => $value) { ?>
                    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
                    <?php } ?>

                    <input type="hidden" name="key" value="">

                    <!-- Key is the file's name on S3 and will be filled in with JS -->
                    <input type="file" name="file" id="file_uploader" >

                    <!-- Progress Bars to show upload completion percentage -->
                    <div class="progress-bar-area progress-bar-striped" style="height: 25px"></div>
                    <textarea class="hidden" id="uploaded"></textarea>
                </form>
                <form action="{{route('files.store')}}" method="post">
                    {!! csrf_field() !!}
                    <label for="caption">File Name</label>
                    <input type="text" class="form-control" name="caption">

                    <input type="hidden" name="size" id="size" value="">
                    <input type="hidden" name="type" id="type" value="">
                    <input type="hidden" name="cn_fileable_type" value="{!! $model_type !!}">
                    <input type="hidden" name="cn_fileable_id" value="{{$model_id}}">

                    <label for="description">Description</label>
                    <textarea name="description" id="description"  class="form-control" rows="5"></textarea>
                    <input type="hidden" name="source" id="source">

                    <br><br>
                    <input type="submit" id="file_submit" class="btn btn-defaul btn-raised disabled" value="Save File">
                </form>
                <!-- This area will be filled with our results (mainly for debugging) -->

            </div>
            </div>
            <div class="row"></div>

            <div class="model-footer hidden" id="addTagFooter">
                <div class="col-sm-12">
                    <input type="submit" id="file_submit" class="btn btn-default btn-raised disabled" value="Save File">
                </div>
            </div>
            <div class="row"></div>

        </div>
    </div>
</div>

@endpush

@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>

<script>
    // Assigned to variable for later use.
    var form = $('.direct-upload');
    var filesUploaded = [];

    // Place any uploads within the descending folders
    // so ['test1', 'test2'] would become /test1/test2/filename
    var folders = ['{{config('schema')}}'];

    var size;
    var type;

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
            var filename = Date.now() + '.' + file.name.split('.').pop();
            form.find('input[name="Content-Type"]').val(file.type);
            form.find('input[name="key"]').val((folders.length ? folders.join('/') + '/' : '') + filename);

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
            window.onbeforeunload = null;

            // Upload Complete, show information about the upload in a textarea
            // from here you can do what you want as the file is on S3
            // e.g. save reference to your server using another ajax call or log it, etc.
            var original = data.files[0];
            var s3Result = data.result.documentElement.children;
            filesUploaded.push(s3Result[0].innerHTML);

            $('#source').val(filesUploaded);
            $('#size').val(size);
            $('#type').val(type);

            $('#file_uploader').addClass('hidden');

            $('#file_submit').removeClass('btn-default disabled');
            $('#file_submit').addClass('btn-success');
        }
    });

    function showImage(id)
    {
        $.ajax({
            url: '{{route('files.index')}}/' + id,
        }).success(function(data){
            var file = '<a href="' + data.source + '" target="_blank"><img style="max-width: 90%" class="model_file" src="' + data.source + '"/></a>'+
                            '<br>';
            @can('citynexus', ['files', 'delete']) file += '<a class="pull-right" href="#" onclick="deleteFile(' + id + ')">' +
                    '<i class="fa fa-trash"></i> </a><br>';
            @endcan

            if(data.description != null) file += '<p>' + data.description + '</p>';
            triggerModal(data.caption, file);

        });
    }

    function downloadFile(id) {
        window.open("{{route('files.download')}}/" + id);
    }

    function deleteFile(id)
    {
        $.ajax({
            url: "{{ route('files.index') }}/" + id,
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                _method: 'DELETE',
            },
            success: function() {
                $('#showImageModal').modal('hide');
                $('#file_item_' + id).remove();
                alert('info', 'File has been removed.');
            }
        })
    }

    function triggerModal(newTitle, newBody)
    {
        $("#image-title").html(newTitle);
        $("#image-body").html(newBody);
        $('#showImageModal').modal('show');
    }

</script>
@endpush

@push('modal')
<div class="modal fade" id="showImageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="image-title"></h4>
            </div>
            <div class="modal-body" id="image-body">
            </div>
        </div>
    </div>
</div>

@endpush