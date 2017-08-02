@extends('master.main')

@section('title', 'Create DropBox Uploader')

@section('main')
    <div class="boxs">
        <div class="boxs-header dvd dvd-btm">
            <h1 class="custom-font">
                <strong>Create New</strong> DropBox Uploader
            </h1>
        </div>
        <div class="boxs-body">
            <form action="{{route('uploader.store')}}" role="form" method="post">
                <div class="row">
                    <div class="col-md-6">
                        {{csrf_field()}}
                        <input type="hidden" name="dataset_id" value="{{$dataset->id}}">
                        <input type="hidden" name="type" value="dropbox">
                        <input type="hidden" name="settings[connection_id]" value="{{$connection->id}}">
                        <input type="hidden" name="settings[path]" id="TargetFolder">
                        <input type="hidden" name="settings[sampleFile]" id="SampleFile">

                        <section class="boxs">
                            <div class="boxs-header dvd dvd-btm">
                                <h1 class="custom-font"><strong>Uploader </strong>Info</h1>

                            </div>
                            <div class="boxs-body">

                                <div class="form-group">
                                    <label for="name">Uploader Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Uploader Description</label>
                                    <textarea type="password" class="form-control" id="description" rows="5" ></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Uploader Frequency</label>
                                    <select type="text" class="form-control" id="frequency" name="frequency">
                                        <option value="intermittent">Intermittent</option>
                                        <option value="hourly">Hourly</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="annually">Annually</option>
                                    </select>
                                </div>

                            </div>
                            <div class="boxs-footer">
                                <input id="createBtn" type="submit" class="btn btn-primary btn-raised" value="Create Uploader" disabled>
                            </div>
                        </section>

                    </div>
                    <div class="col-md-6">
                        <div id="sql_settings">
                            <div class="alert alert-info">
                                Before you start uploading, make sure your file is in the correct format for use in CityNexus.
                                Each data set must have a header row with unique headers, and each row should have some sort
                                of identifying information like an address, property id, or lot number.
                            </div>
                            <div style="height: 40px">
                                <div class="hidden" id="targetFolderPreview"></div>
                                <div class="hidden" id="sampleFilePreview"></div>
                            </div>

                            <div class="form-horizontal" id="dropboxInfo">
                                <div>
                                    <span role="button" class="fa fa-level-up" onclick="goBack()"></span> <span id="currentPath"></span> <div class="btn btn-xs btn-primary btn-raised hidden" id="setFolder" onclick="">Set Folder</div>
                                </div>
                                <div class="list-group" style="max-height: 300px; overflow: scroll" id="list-group">

                                </div>
                            </div>
                        </div>
                        <div class="form-horizontal hidden" id="table_settings">

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    </div>

@endsection


@push('style')

@endpush

@push('scripts')
<script>

    var files;
    var last = null;
    var current = null;

    $(document).ready(function(){
        getFileList();
    });

    var goBack = function() {
        var path = current.split("/");
        path.slice(-1);
        if(path.length = 1)
            getFileList();
        else
            getFileList(path.join('/'));
    };


    var getFileList = function( path = null, showFiles = false )
    {
        current = path;


        if(path == null)
        {
            $('#setFolder').addClass('hidden');
            var data = "{\"path\": \"\"}";
        } else {
            $('#setFolder').attr('onclick', 'setFolder(\'' + path + '\')');
            $('#setFolder').removeClass('hidden');
            var data = "{\"path\": \"/" + path + "\"}";
        }

        // Get file list
        $.ajax({
            url: "https://api.dropboxapi.com/2/files/list_folder",
            type: 'POST',
            headers: {
                "Authorization": "Bearer {{$connection->settings['access_token']}}",
                "Content-Type": "application/json"
            },
            data: data,
            success: function( returnData ) {

                $('#currentPath').html(current);

                files = returnData.entries;

                var listGroup = $('#list-group');

                listGroup.html("");

                for(var i = 0; i < files.length; i++)
                {
                    if(!showFiles && files[i]['.tag'] == 'folder')
                    {
                        var newItem = '<div role="button" class="list-group-item" onclick="getFileList(\'' + files[i]['path_lower'] + '\')"><span class="fa fa-folder"></span> ' + files[i]['name'] + '</div>';
                    }
                    if(!showFiles && files[i]['.tag'] == 'file')
                    {
                        var newItem = '<div class="list-group-item"><span class="fa fa-file"></span> ' + files[i]['name'] + '</div>';
                    }
                    else if (showFiles && files[i]['.tag'] == 'file')
                    {
                        var newItem = '<div class="list-group-item"><span class="fa fa-file"></span> ' + files[i]['name'] + ' <div class="btn btn-xs btn-primary btn-raised" onclick="setFile(\'' + files[i]['name'] + '\', \'' + files[i]['path_lower'] + '\')">Set Sample File</div></div>';
                    }
                    listGroup.append(newItem);

                }
            },
            error: function (data) {
                alert(JSON.stringify(data));
            }
        });


    }

    var setFolder = function(path)
    {
        $('#setFolder').addClass('hidden');
        $('#TargetFolder').val(path);
        $('#targetFolderPreview').removeClass('hidden').html('<b>Target Folder: </b> ' + path + ' <span role="button" style="color: red" class="fa fa-times-circle" onclick="removeFolder()"></span>');
        getFileList(path, true);
    };

    var setFile = function(name, path)
    {
        $('#createBtn').attr('disabled', false);
        $('#SampleFile').val(path);
        $('#sampleFilePreview').removeClass('hidden').html('<b>Sample File: </b> ' + name + ' <span role="button" style="color: red" class="fa fa-times-circle" onclick="removeFile()"></span>');
        $('#dropboxInfo').addClass('hidden');
    };

    var removeFile = function () {
        $('#Samplefile').val('');
        $('#sampleFilePreview').addClass('hidden');
        $('#dropboxInfo').removeClass('hidden');
        $('#createBtn').attr('disabled', true);
        getFileList(current, true);
    };

    var removeFolder = function () {
        removeFile();
        $('#TargetFolder').val('');
        $('#targetFolderPreview').addClass('hidden');
        getFileList(current, false);
    };
</script>
@endpush