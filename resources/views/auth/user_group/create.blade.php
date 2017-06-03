
@if(isset($userGroup))
    @section('title', "Edit " . $userGroup->name . " Group")
@else
    @section('title', "Create New Group")
@endif

@extends('master.main')

@php
$permission_sets = [
        [
                'title' => 'Data Sets',
                'key' => 'datasets',
                'permissions' => [
                        [
                                'permission' => "View Data Set List",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "Create Data Set",
                                'key' => 'create'
                        ],
                        [
                                'permission' => "Edit Data Sets",
                                'key' => 'edit'
                        ],
                        [
                                'permission' => "Upload Data",
                                'key' => 'upload'
                        ],
                        [
                                'permission' => "Rollback Uploads",
                                'key' => 'rollback'
                        ],
                        [
                                'permission' => 'Create Uploader',
                                'key' => 'create-uploader'
                        ],
                        [
                                'permission' => "Delete Data Set",
                                'key' => 'delete'
                        ],
                ]
        ],

        [
                'title' => 'Organization Admin',
                'key' => 'org-admin',
                'permissions' => [
                        [
                                'permission' => "View Admin Panel",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "Create and Edit Groups",
                                'key' => 'groups'
                        ],
                        [
                                'permission' => "Create Users",
                                'key' => 'create-users'
                        ],
                        [
                                'permission' => "Edit Users",
                                'key' => 'edit-users'
                        ],
                        [
                                'permission' => "Remove Users",
                                'key' => 'remove-users'
                        ],
                        [
                                'permission' => "Assign Users to Groups",
                                'key' => 'assign-groups'
                        ],
                ]
        ],

]
@endphp

@section('main')

    <div class="row">
        <div class="col-sm-12">
            @unless(isset($userGroup))
            <form action="{{action('Auth\UserGroupController@store')}}" method="post" class="form-horizontal">
            @else
            <form action="{{action('Auth\UserGroupController@update', [$userGroup->id])}}" method="post" class="form-horizontal">
            @endunless
                {!! csrf_field() !!}

                <section class="boxs">
                <div class="boxs-header dvd dvd-btm">
                    @unless(isset($userGroup))
                    <h1 class="custom-font"><strong>Create</strong> New Group</h1>
                    @else
                    <h1 class="custom-font">Edit <strong>{{$userGroup->name}}</strong> Group</h1>
                    <input type="hidden" name="_method" value="patch">
                    @endunless
                </div>
                <div class="boxs-body">
                        <div class="form-group">
                            <label for="name" class="control-label col-sm-3">User Group Name</label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" @if(isset($userGroup)) value="{{$userGroup->name}}" @else value="{{old('name')}}" @endif/>
                            </div>
                        </div>

                        <div class="col-sm-offset-3">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                {{--Data sets--}}

                                @foreach($permission_sets as $i)
                                    <?php $group = $i['key']; ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#{{$i['key']}}" aria-expanded="true" aria-controls="collapseOne">
                                                    {{$i['title']}}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="{{$i['key']}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="list-group">
                                                    @foreach($i['permissions'] as $p)
                                                        <?php $method = $p['key']; ?>
                                                        <div class="list-group-item">
                                                            <input type="checkbox" name="permissions[{{$i['key']}}][{{$p['key']}}]" value="true" class="{{$i['key']}}"
                                                                   @if(isset($permissions[$group][$method])) checked @endif
                                                            > <label for="">{{ $p['permission'] }}</label>
                                                        </div>
                                                    @endforeach
                                                    <br><br>
                                                    <button class="btn btn-raised btn-sm" onclick="select('{{$i['key']}}')" id="{{$i['key']}}SelectAll"> Check All </button>
                                                    <button class="btn btn-raised btn-sm hidden" onclick="clearChecks('{{$i['key']}}')" id="{{$i['key']}}UnselectAll"> Uncheck All </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    <div class="boxs-footer">
                        @unless(isset($userGroup))
                        <input type="submit" class="btn btn-raised btn-primary" value="Create Group">
                        @else
                        <input type="submit" class="btn btn-raised btn-primary" value="Update Group">
                        @endunless
                    </div>
                </div>
            </section>
        </form>
        @if(isset($userGroup))
        <div class="boxs-footer">
            <form action="{{action('Auth\UserGroupController@destroy', [$userGroup->id])}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="delete">
                <button class="btn btn-raised btn-danger">Delete Group</button>
            </form>
        </div>
        @endif
    </div>

@stop

@push('scripts')

<script>
    function select( type  )
    {
        event.preventDefault();
        $('.' + type).prop("checked", true);
        $('#' + type + 'SelectAll').addClass('hidden');
        $('#' + type + 'UnselectAll').removeClass('hidden');
    }

    function clearChecks( type  )
    {
        event.preventDefault()
        $('.' + type).prop("checked", false);
        $('#' + type + 'SelectAll').removeClass('hidden');
        $('#' + type + 'UnselectAll').addClass('hidden');
    }
</script>

@endpush

@push('style')


@endpush
