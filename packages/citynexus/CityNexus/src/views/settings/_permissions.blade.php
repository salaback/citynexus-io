<?php

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
@if(isset($user))
<input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
{{csrf_field()}}
<div class="form-horizontal">
<div class="form-group">
    <label for="first_name" class="control-label col-sm-4">First Name</label>

    <div class="col-sm-8">
        <input type="text" class="form-control" id="first_name" name="first_name" value="{{$user->first_name}}"/>
    </div>
</div>
<div class="form-group">
    <label for="last_name" class="control-label col-sm-4">Last Name</label>

    <div class="col-sm-8">
        <input type="text" class="form-control" id="last_name" name="last_name" value="{{$user->last_name}}"/>
    </div>
</div>

<div class="form-group">
    <label for="title" class="control-label col-sm-4">Title</label>

    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="title" value="{{$user->title}}"/>
    </div>
</div>

<div class="form-group">
    <label for="department" class="control-label col-sm-4">Department</label>

    <div class="col-sm-8">
        <input type="text" class="form-control" id="department" name="department" value="{{$user->department}}"/>
    </div>
</div>
</div>

@endif

<h4>Groups Membership</h4>

<div class="list-group" id="user_groups">
    @forelse($user->groups as $group)
        <div class="list-group-item" >
            @include('auth.user_group._group_snip')
        </div>
    @empty
        <div class="list-group-item disabled" id="noGroups">User isn't in any groups</div>
    @endforelse
</div>

<div class="row">
    <div class="col-xs-9">
        <select name="group" id="group" class="form-control">
            <option value="">Select One</option>
            @foreach($groups as $group)
                <option value="{{$group->id}}">{{$group->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xs-3">
        <button class="btn btn-primary" onclick="addToGroup()">
            Add to Group
        </button>
    </div>
</div>
