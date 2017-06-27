<?php

@php
    $permission_sets = [
             [
                    'title' => 'Data Visualization',
                    'key' => 'dataviz',
                    'permissions' => [
                            [
                                    'permission' => "View Module",
                                    'key' => 'view'
                            ],
                            [
                                    'permission' => "Map Builder",
                                    'key' => 'maps'
                            ]
                    ]
             ],
             [
                    'title' => 'Data Analytics',
                    'key' => 'analytics',
                    'permissions' => [
                            [
                                    'permission' => "Create Scores",
                                    'key' => 'score-create'
                            ],
                            [
                                    'permission' => "Edit Scores",
                                    'key' => 'score-edit'
                            ],
                            [
                                    'permission' => "Delete Scores",
                                    'key' => 'score-delete'
                            ]
                    ]
             ],
             [
                    'title' => 'Entities',
                    'key' => 'entities',
                    'permissions' => [
                            [
                                    'permission' => "View Entities",
                                    'key' => 'view'
                            ],
                            [
                                    'permission' => "Create Entities",
                                    'key' => 'create'
                            ],
                            [
                                    'permission' => "Edit Entities",
                                    'key' => 'edit'
                            ],
                            [
                                    'permission' => "Delete Entities",
                                    'key' => 'delete'
                            ],
                    ]
             ],
             [
                    'title' => 'Files',
                    'key' => 'files',
                    'permissions' => [
                            [
                                    'permission' => "View Files",
                                    'key' => 'view'
                            ],
                            [
                                    'permission' => "Upload Files",
                                    'key' => 'upload'
                            ],
                            [
                                    'permission' => "Delete Files",
                                    'key' => 'delete'
                            ]
                    ]
             ],
             [
                    'title' => 'Properties',
                    'key' => 'properties',
                    'permissions' => [
                            [
                                    'permission' => "View Properties",
                                    'key' => 'view'
                            ],
                            [
                                    'permission' => "Create Properties",
                                    'key' => 'create'
                            ],
                            [
                                    'permission' => "Edit & Merge Properties",
                                    'key' => 'edit'
                            ],
                            [
                                    'permission' => "Comment on Properties",
                                    'key' => 'comment'
                            ],
                            [
                                    'permission' => "Tag Properties",
                                    'key' => 'tag'
                            ]
                    ]
            ],
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
                            [
                                    'permission' => 'Delete User comments',
                                    'key' => 'delete-comments'
                            ]
                    ]
            ],

    ];
@endphp