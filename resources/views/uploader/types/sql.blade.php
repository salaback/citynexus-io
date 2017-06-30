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
        <section class="boxs">
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
                    <tbody>
                    @php($uploads = $uploader->uploads()->paginate(10))
                    @foreach($uploads as $upload)
                        <tr class="
                        @if($upload->processed_at == null)
                                warning
                            @endif
                                ">
                            <td>{{$upload->description}}</td>
                            <td>{{$upload->created_at->toFormattedDateString()}}</td>
                            <td>
                                @if($upload->processed_at != null)
                                    {{$upload->processed_at->toFormattedDateString()}}
                                @else

                                @endif
                            </td>
                            <td>{{$upload->user->fullname}}</td>
                            <td><div class="dropup pull-right">
                                    <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-navicon" id="icon-{{$upload->id}}"></i>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{$upload->source}}" target="_blank"><i class="fa fa-download"></i> Download Original File</a></li>

                                        <li class="divider"></li>
                                        <li><a href="#" onclick="refreshUpload({{$upload->id}})" id="refresh-{{$upload->id}}"><i class="fa fa-refresh"></i> Refresh Upload</a></li>
                                        <li><a href="#"> <i class="fa fa-trash"></i> Remove Upload</a></li>
                                    </ul>
                                </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $uploads->links() }}
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>SQL </strong>Settings</h1>
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
                    <div class="form-group">
                        <label for="exampleInputEmail1">Table Name</label>
                        <input type="text" name="settings[table_name]" class="form-control" id="table_name" value="{{$uploader->settings['table']}}">
                        <span class="material-input"></span></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Host</label>
                        <input type="text" name="settings[host]" class="form-control" id="host" value="{{$uploader->settings['db']['host']}}">
                        <span class="material-input"></span></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Host</label>
                        <select name="settings[driver]" class="form-control mb-10 parsley-success" data-parsley-trigger="change" required="" data-parsley-id="8432">
                            <option value="">Select option...</option>
                            <option value="pgsql" @if($uploader->settings['db']['driver'] == 'pgsql') selected @endif selected>Postgres</option>
                            <option value="mysql" @if($uploader->settings['db']['driver'] == 'mysql') selected @endif >MySQL</option>
                        </select>
                        <span class="material-input"></span></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Database Name</label>
                        <input type="text" name="settings[database]" class="form-control" id="database" value="{{$uploader->settings['db']['database']}}">
                        <span class="material-input"></span></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">User Name</label>
                        <input type="text" name="settings[username]" class="form-control" id="username" value="{{$uploader->settings['db']['username']}}">
                        <span class="material-input"></span></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Password</label>
                        <input type="password" name="settings[password]" class="form-control" id="password" value="{{$uploader->settings['db']['password']}}">
                        <span class="material-input"></span></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Schema</label>
                        <input type="text" name="settings[schema]" class="form-control" id="schema" value="{{$uploader->settings['db']['schema']}}">
                        <span class="material-input"></span></div>
                    <button type="submit" class="btn btn-raised btn-primary">Update Settings</button>
                </form>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Data Sync Methods</strong></h1>
                <ul class="controls">
                    <li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-plus"></i> Create New Sync<i class="fa fa-angle-down ml-5"></i></a>
                        <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                            <li><a href="{{route('uploader.addressSync', [$uploader->id])}}"> Create Address Sync</a></li>
                            <li><a href="{{route('uploader.entitySync', [$uploader->id])}}"> Create Entity Sync</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="boxs-body">

                <div class="inbox-widget nicescroll" style="height: 315px; overflow: hidden; outline: none;" tabindex="5000">
                    @if($uploader->syncs != null)
                        @foreach($uploader->syncs as $sync)

                            <div class="inbox-item">
                                <form action="{{route('uploader.removeSync', [$uploader->id])}}" method="post">
                                    {{csrf_field()}}
                                    <button role="button" href="#" class="pull-right"><span class="fa fa-trash"></span></button>
                                    <input type="hidden" name="sync" value="{{json_encode($sync)}}">
                                </form>
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
                                            @if(isset($sync['house_number']))Street Number: <span class="label label-default">{{$sync['house_number']}}</span>@endif
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
<div class="modal fade" id="newImportModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{route('upload.store')}}" method="post">
            {{csrf_field()}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Import</h4>
                <input type="hidden" name="file_type" value="sql">
            </div>
            <div class="modal-body">
                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                    <div class="radio">
                        <label>
                            <input type="radio" name="settings[scope]" id="scope_all" value="all" checked>
                                Sync all records with source database
                            </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="settings[scope]" id="scope_sinceLast" value="sinceLast">
                                Sync changes since last upload
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="settings[scope]" id="scope_added_between" value="addedBetween">
                            Sync records added between dates
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name='settings[created_start]' class="form-control">
                                </div>

                                <div class="col-sm-6">
                                    <input type="text" name='settings[created_end]' class="form-control">
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="settings[scope]" id="scope_edited_between" value="editedBetween">
                            Sync records edited between dates:
                            <div class="row">
                               <div class="col-sm-6">
                                   <input type="text" name='settings[edited_start]' class="form-control">
                               </div>

                                <div class="col-sm-6">
                                <input type="text" name='settings[edited_end]' class="form-control">
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="settings[scope]" id="scope_unique_between" value="uniqueBetween">
                            Sync records with an unique ID between
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="number" name='settings[unique_start]' class="form-control">
                                </div>

                                <div class="col-sm-6">
                                    <input type="number" name='settings[unique_end]' class="form-control">
                                </div>
                            </div>
                        </label>
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

</script>
@endpush