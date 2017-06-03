@extends('master.main')

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

@section('title', 'Create CSV/Excel Uploader')

@section('main')
        <div class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font">
                    <strong>Create New</strong> CSV/Excel Uploader
                </h1>
            </div>
            <div class="boxs-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{route('uploader.store')}}" role="form" method="post">
                            {{csrf_field()}}
                            <section class="boxs">
                                <div class="boxs-header dvd dvd-btm">
                                    <h1 class="custom-font"><strong>Uploader </strong>Info</h1>

                                </div>
                                <div class="boxs-body">

                                        <div class="form-group is-empty">
                                            <label for="name">Uploader Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                            <span class="material-input"></span></div>
                                        <div class="form-group is-empty">
                                            <label for="description">Uploader Description</label>
                                            <textarea type="password" class="form-control" id="description" rows="5" ></textarea>
                                            <span class="material-input"></span></div>

                                        <input type="hidden" name="upload[source]" id="uploadSource">
                                        <input type="hidden" name="upload[size]" id="uploadSize">
                                        <input type="hidden" name="upload[note]" id="uploadNote">
                                        <input type="hidden" name="upload[file_type]" id="uploadType">
                                        <input type="hidden" name="type" value="csv">
                                        <input type="hidden" name="dataset_id" value="{{$_GET['dataset_id']}}">
                                </div>
                                <div class="boxs-footer">
                                    <span id="uploadFirst" class="alert alert-info">Upload a CSV/Excel File before saving.</span>
                                </div>
                            </section>
                        </form>

                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            Before you start uploading, make sure your file is in the correct format for use in CityNexus.
                            Each data set must have a header row with unique headers, and each row should have some sort
                            of identifying information like an address, property id, or lot number.
                        </div>
                            <div class="form-group" id="upload_note_wrapper">
                                <label for="name">Upload Description</label>
                                <input type="text" class="form-control" id="upload_note" name="upload_note" value="Initial Upload">
                                <span class="material-input"></span>
                            </div>
                        <form action="<?php echo $s3FormDetails['url']; ?>"
                              method="POST"
                              enctype="multipart/form-data"
                              class="direct-upload"
                              id="file_upload_form">
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
                        <div id="progressWrapper" class="hidden">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%;" id="uploadProgress">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection


@push('style')
<link href="/vendor/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

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
            $('#file_upload_form').addClass('hidden');
            $('#upload_note_wrapper').addClass('hidden');
            $('#progressWrapper').removeClass('hidden');
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
            $('#uploadProgress').css('width', percent + '%').html(percent + '% Complete');
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

            $('#uploadSource').val(name);
            $('#uploadSize').val(size);
            $('#uploadType').val(type);
            $('#uploadNote').val($('#upload_note').val());
            $('#uploadFirst').replaceWith('<button type="submit" class="btn btn-raised btn-primary">Create New Uploader</button>');
            alert('success', 'File has been successfully uploaded.');

        }
    });
</script>
@endpush