<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    /*
     * -----------------------------------------------------------------------------
     * Permissions
     * -----------------------------------------------------------------------------
     *
     */
    'permissions' => [
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

    ]

];
