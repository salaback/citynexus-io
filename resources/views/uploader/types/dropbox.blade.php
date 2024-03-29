@extends('master.main')

@section('title', "Edit Uploader: " . $uploader->name)

@section('main')
    <div class="col-md-12">
        <a href="{{route('dataset.show', [$uploader->dataset_id])}}" class="btn btn-raised btn-primary"><span class="fa fa-angle-left"></span> Back to Data Set</a>

        @if($uploader->frequency == 'intermittent')
            <button class="btn btn-raised btn-primary pull-right"  data-toggle="modal" data-target="#newImportModal"><span class="glyphicon glyphicon-import"></span> New Import</button>
        @endif

    </div>
    <div class="col-md-12">
        @include('uploader.snipits._uploads')
    </div>

    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Drop Box </strong>Settings</h1>
            </div>
            <div class="boxs-body">
                <form role="form" action="{{route('uploader.update', [$uploader->id])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PATCH')}}

                    <div class="form-group">
                        <label for="frequency">Uploader Frequency</label>
                        <select type="text" class="form-control" id="frequency">
                            <option value="intermittent" @if($uploader->frequency ==' intermittent') select @endif>Intermittent</option>
                            <option value="hourly" @if($uploader->frequency ==' intermittent') select @endif>Hourly</option>
                            <option value="daily" @if($uploader->frequency ==' intermittent') select @endif>Daily</option>
                            <option value="weekly" @if($uploader->frequency ==' intermittent') select @endif>Weekly</option>
                            <option value="monthly" @if($uploader->frequency ==' intermittent') select @endif>Monthly</option>
                            <option value="quarterly" @if($uploader->frequency ==' intermittent') select @endif>Quarterly</option>
                            <option value="annually" @if($uploader->frequency ==' intermittent') select @endif>Annually</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-raised btn-primary">Update Settings</button>
                </form>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        @include('uploader.snipits._data_sync_methods')
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
<div class="modal fade" id="newImportModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{route('upload.store')}}" class="horizontal-form" method="post">
            {{csrf_field()}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Import</h4>
                <input type="hidden" name="file_type" value="sql">
            </div>
            <div class="modal-body">
                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                    <div class="row">
                        <div class="from-group">
                            <label for="description" class="control-label col-sm-3">Description</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="note" value="{{\Illuminate\Support\Facades\Auth::user()->fullname}}'s upload from {{date('D d M y')}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group" id="fileList"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-raised btn-success" value="Begin Import">
                <button type="button" class="btn btn-raised btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>

        </div>
    </div>
</div>
@endpush

@push('scripts')

<script>

    var refreshUpload = function(id)
    {
        var icon = $('#icon-' + id);

        icon.addClass('fa-spinner fa-spin').removeClass('fa-navicon');
        $.ajax({
            url: "{{route('upload.process')}}/" + id,
            success: function () {
                icon.removeClass('fa-spinner fa-spin').addClass('fa-navicon');
            },
            fail: function(data) {
                console.log(data);
                alert('An error has occured in refreshing data. Please see console');
                icon.removeClass('fa-spinner fa-spin').addClass('fa-alert');

            }
        })
    }

    // Get file list

    var data = "{\"path\": \"{{$uploader->settings['path']}}\"}";

    $.ajax({
        url: "https://api.dropboxapi.com/2/files/list_folder",
        type: 'POST',
        headers: {
            "Authorization": "Bearer {{\App\DataStore\Model\Connection::find($uploader->settings['connection_id'])->settings['access_token']}}",
            "Content-Type": "application/json"
        },
        data: data,
        success: function( returnData ) {

            var files = returnData.entries;

            var listGroup = $('#fileList');

            listGroup.html("");

            for(var i = 0; i < files.length; i++)
            {
                if (files[i]['.tag'] == 'file')
                {
                    var newItem = '<div class="list-group-item"><input type="checkbox" name="settings[files][]" value="' + files[i]['path_lower'] +'"> ' + files[i]['name'] + '</div>';
                }
                listGroup.append(newItem);

            }
        },
        error: function (data) {
            alert(JSON.stringify(data));
        }
    });

</script>
@endpush