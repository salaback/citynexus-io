<?php
$pagename = 'Create User Group';
$section = 'admin';

$permission_sets = [
        [
                'title' => 'Datasets',
                'key' => 'datasets',
                'permissions' => [
                        [
                                'permission' => "View Dataset",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "View Raw Dataset",
                                'key' => 'raw'
                        ],
                        [
                                'permission' => "Create New Dataset",
                                'key' => 'create'
                        ],
                        [
                                'permission' => "Upload to Dataset",
                                'key' => 'upload'
                        ],
                        [
                                'permission' => "Edit Dataset",
                                'key' => 'edit'
                        ],
                        [
                                'permission' => "Delete Dataset",
                                'key' => 'delete'
                        ],
                        [
                                'permission' => "Export Dataset",
                                'key' => 'export'
                        ],
                        [
                                'permission' => "Rollback Upload",
                                'key' => 'rollback'
                        ]

                ]
        ],
        [
                'title' => 'Scores',
                'key' => 'scores',
                'permissions' => [
                        [
                                'permission' => "View Score",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "View Raw Scores",
                                'key' => 'raw'
                        ],
                        [
                                'permission' => "Create New Score",
                                'key' => 'create'
                        ],
                        [
                                'permission' => "Refresh Score",
                                'key' => 'refresh'
                        ],
                        [
                                'permission' => "Edit Score",
                                'key' => 'edit'
                        ],
                        [
                                'permission' => "Delete Score",
                                'key' => 'delete'
                        ],
                        [
                                'permission' => "Upload Score",
                                'key' => 'upload'
                        ]
                ]
        ],
        [
                'title' => 'Reports',
                'key' => 'reports',
                'permissions' => [
                        [
                                'permission' => "View Reports",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "Create Reports",
                                'key' => 'create'
                        ],
                        [
                                'permission' => "Save Reports",
                                'key' => 'save'
                        ],
                        [
                                'permission' => "Delete Score",
                                'key' => 'score'
                        ],
                ]
        ],
        [
                'title' => 'Users',
                'key' => 'usersAdmin',
                'permissions' => [
                        [
                                'permission' => "Create User",
                                'key' => 'create'
                        ],
                        [
                                'permission' => "Delete User",
                                'key' => 'delete'
                        ],
                        [
                                'permission' => "Assign User Permissions",
                                'key' => 'assign'
                        ],
                ]
        ],
        [
                'title' => 'Properties',
                'key' => 'properties',
                'permissions' => [
                        [
                                'permission' => "View Properties List",
                                'key' => 'view',
                        ],
                        [
                                'permission' => "View Properties Details",
                                'key' => 'show',
                        ],
                        [
                                'permission' => "Merge Properties",
                                'key' => 'merge',
                        ],
                        [
                                'permission' => "Edit Properties Record",
                                'key' => 'edit',
                        ],
                        [
                                'permission' => "Create Properties Record",
                                'key' => 'create',
                        ]
                ]
        ],
        [
                'title' => 'Export',
                'key' => 'export',
                'permissions' => [
                        [
                                'permission' => "View & Download Exports",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "Create New Exports",
                                'key' => 'create'
                        ]
                ]
        ],
        [
                'title' => 'Administrator',
                'key' => 'admin-rights',
                'permissions' => [
                        [
                                'permission' => "View Admin Panel",
                                'key' => 'view'
                        ],
                        [
                                'permission' => "Hard Delete Data",
                                'key' => 'delete'
                        ],
                        [
                                'permission' => "Edit App Settings",
                                'key' => 'edit'
                        ]
                ]
        ]
]
?>

@extends(config('citynexus.template'))

@section(config('citynexus.section'))

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <form action="{{action('Auth\UserGroupController@store')}}" method="post" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="name" class="control-label col-sm-4">User Group Name</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}"/>
                        </div>
                    </div>

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
                                                           @if(isset($permissions->$group->$method)) checked @endif
                                                    > <label for="">{{ $p['permission'] }}</label>
                                                </div>
                                            @endforeach
                                            <br><br>
                                            <button class="btn btn-sm" onclick="select('{{$i['key']}}')" id="{{$i['key']}}SelectAll"> Check All </button>
                                            <button class="btn btn-sm hidden" onclick="clearChecks('{{$i['key']}}')" id="{{$i['key']}}UnselectAll"> Uncheck All </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    @endforeach

                        <input type="submit" class="btn btn-primary" value="Create">
                </form>
            </div>
        </div>
    </div>

@stop

@push('js_footer')

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