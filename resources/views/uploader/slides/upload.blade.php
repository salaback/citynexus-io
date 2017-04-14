<?php

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

?>

<h3>Upload from CSV or Excel</h3>
<div class="row">
    <div class="alert alert-info">
        Before you start uploading, make sure your file is in the correct format for use in CityNexus.
        Each data set must have a header row with unique headers, and each row should have some sort
        of identifying information like an address, property id, or lot number.
    </div>

    <div class="row">
        <div class="col-sm-12">
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
    </div>
</div>

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
                    uploader_id: uploader_id,
                    note: $('#upload_note').val(),
                    slug: 'upload',
                    _token: '{{csrf_token()}}'
                }
            }).success(function (data) {
                upload_id = data.id;
                $.ajax({
                    url: "{{route('uploader.schema')}}",
                    method: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        uploader_id: uploader_id,
                        upload_id: upload_id,
                        dataset_id: dataset_id
                    },
                    success: function (data) {
                        swapslides(data);
                    }
                });
            });
        }
    });
</script>