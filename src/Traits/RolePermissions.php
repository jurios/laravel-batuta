<?php


namespace Kodilab\LaravelBatuta\Traits;


use Illuminate\Foundation\Auth\User;
use Kodilab\LaravelBatuta\Models\Action;

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
     * Returns whether the role has permission or not for the given action
     *
     * @param Action $action
     * @return bool
     */
    public function hasPermission(Action $action)
    {
        if ($this->isGod()) {
            return true;
        }

        if (!is_null($permission = $this->actions()->find($action->id))) {
            return $permission->pivot->permission;
        }

        return false;
    }

    /**
     * @return string
     */
    public static function getPermissionsTable()
    {
        return config('batuta.tables.role_permissions', 'batuta_role_permissions');
    }
}