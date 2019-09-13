<?php


namespace Kodilab\LaravelBatuta\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Pivots\Permission;

trait UserPermissions
{
    use HasPermissions;

    public function getPermissionsTable()
    {
        return config('batuta.tables.user_permissions', 'user_permissions');
    }
}