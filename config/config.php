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
        'user_permissions'  => 'user_permissions'
    ]
];
