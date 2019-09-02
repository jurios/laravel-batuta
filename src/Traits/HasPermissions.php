<?php


namespace Kodilab\LaravelBatuta\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Pivots\Permission;

trait HasPermissions
{
    public function actions()
    {
        return $this->belongsToMany(Action::class, self::getPermissionsTable())
            ->using(Permission::class)
            ->withPivot(['permission']);
    }

    /**
     * Updates a permission (or create if it does not exist). If detaching is true, then the previous permissions are
     * removed.
     *
     * @param array $permissions
     * @param bool $detaching
     */
    public function updatePermissions(array $permissions, bool $detaching = false)
    {
        $permissions = array_map(function ($item) {
            return ['permission' => $item];
        }, $permissions);

        $this->actions()->sync($permissions, $detaching);
        $this->refresh();
    }

    /**
     * Returns whether it has permission or not for the given action
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

        if (method_exists($this, 'roles')) {
            /** @var Role $role */
            foreach ($this->roles as $role) {
                if ($role->hasPermission($action)) {
                    return true;
                }
            }
        }

        return false;
    }
}