<?php


namespace Kodilab\LaravelBatuta\Traits;



trait RolePermissions
{
    use HasPermissions;

    /**
     * Returns the name of the permissions table
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getPermissionsTable()
    {
        return config('batuta.tables.role_permissions', 'role_permissions');
    }
}