<?php


namespace Kodilab\LaravelBatuta\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;

trait UserPermissions
{
    use HasPermissions;

    public function roles()
    {
        return $this->belongsToMany(
            Role::class, config('batuta.tables.role_user', 'batuta_role_user')
        );
    }

    /**
     * Update the roles assigned
     *
     * @param array $roles
     * @param bool $detaching
     */
    public function updateRoles(array $roles, bool $detaching = false)
    {
        $this->roles()->sync($roles, $detaching);
    }

    /**
     * Add a role
     *
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        $this->roles()->sync([$role->id], false);
        $this->refresh();
    }

    /**
     * Remove a role
     *
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles()->detach($role->id);
        $this->refresh();
    }

    /**
     * Remove all roles
     */
    public function removeAllRoles()
    {
        $this->roles()->detach();
        $this->refresh();
    }

    /**
     * Returns whether the given role is associated
     *
     * @param Role $role
     * @return bool
     */
    public function hasRole(Role $role)
    {
        return !is_null($this->roles()->find($role->id));
    }

    /**
     * Returns whether the user has permission or not for the given action
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

        /** @var Role $role */
        foreach ($this->roles as $role) {
            if ($role->hasPermission($action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns whether the user belongs to a god role
     *
     * @return bool
     */
    public function isGod()
    {
        return $this->roles()->where('god', true)->get()->isNotEmpty();
    }

    /**
     * Returns the user table name
     *
     * @return string
     */
    public static function getPermissionsTable()
    {
        return config('batuta.user_permissions', 'batuta_user_permissions');
    }
}