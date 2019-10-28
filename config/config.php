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
    | God attribute
    |--------------------------------------------------------------------------
    |
    | A Role with "god" flag set to true will have all permission granted in case you
    | set 'allow_god' to true.
    |
    */
    'allow_god' => true,

    /*
    |--------------------------------------------------------------------------
    | Role inheritance
    |--------------------------------------------------------------------------
    |
    | When a permission is not set for a user (that means, is 'null'), by default
    | the user's role permissions are inherited. You can disable the behaviour setting
    | this parameter to false.
    |
    */
    'role_inheritance' => true,
];
