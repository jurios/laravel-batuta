<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    |
    | Laravel-batuta will create 4 table. Here you can edit the name for
    | each table in order to fit your database. Next, you can see what is each
    | table for:
    |
    | "actions" table: Contains all the actions for each resource
    | "roles": Contains all the roles created (included roles created by Laravel-batuta)
    | "user_permissions": Contains all the permissions assigned to each user
    | "role_permissions": Contains all the permissions assigned to each role
    */
    'tables' => [
        'actions'           => 'actions',
        'users'             => 'users',
        'roles'             => 'roles',
        'role_user'         => 'role_user',
        'user_permissions'  => 'user_permissions',
        'role_permissions'  => 'role_permissions'
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | You must define two initial roles. Those roles will be created automatically
    | for you. Here you can define a custom name for that roles.
    |
    | - God: This role will be granted for all permissions. You can not revoke any permission to this role.
    |
    | - Default: This role will be the role assigned to a user when is created. By default this role
    | don't have any permission granted. You should grant permissions manually afterwards.
    |
    */
    'roles' => [
        'god' => [
            'name' => 'god'
        ],

        'default' => [
            'name' => 'default'
        ]
    ]
];
