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
    | "resources" table: Contains all the resources created
    | "actions" table: Contains all the actions for each resource
    | "users" table: Contains all users (by default Laravel creates table 'users')
    | "roles": Contains all the roles created (included roles created by Laravel-batuta)
    | "role_permissions": Contains all the permissions assigned to each role
    |
    */
    'tables' => [
        'actions'           => 'actions',
        'users'             => 'users',
        'user_permissions'  => 'user_permissions'
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | In case you are not using the User model provided by Laravel, here you
    | can define your our custom User model
    |
    */
    'user_model' => \App\User::class,

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | Laravel-batuta will create 2 basic roles. Here you can edit the name for
    | each basic roles:
    |
    | "god": The god role is a role which has permissions for do all actions.
    | "default" table: This is the role which will be assigned if a user is persisted
    |                   without a role assigned.
    |
    */
    'roles' => [
        'god' => 'god',
        'default' => 'user'
    ]
];
