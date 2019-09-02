<?php


namespace Kodilab\LaravelBatuta\Traits;


use Illuminate\Foundation\Auth\User;

trait RolePermissions
{
    use HasPermissions;

    public function users()
    {
        return $this->belongsToMany(
            User::class, config('batuta.tables.role_user', 'batuta_role_user')
        );
    }

    /**
     * @return string
     */
    public static function getPermissionsTable()
    {
        return config('batuta.tables.role_permissions', 'batuta_role_permissions');
    }
}