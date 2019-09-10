<?php


namespace Kodilab\LaravelBatuta\Traits;


use Illuminate\Support\Str;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Pivots\Permission;

trait HasPermissions
{
    /**
     * Actions relationship (permissions)
     *
     * @return mixed
     */
    public function actions()
    {
        $table = method_exists($this, 'getPermissionsTable') ?
            $this->getPermissionsTable() : $this->guessPermissionsTable();

        return $this->belongsToMany(Action::class, $table)->using(Permission::class)->withPivot(['permission']);
    }

    /**
     * By convention, the permission table for a model is "model_permissions".
     */
    private function guessPermissionsTable()
    {
        return Str::snake(class_basename($this)) . '_permissions';
    }

    /**
     * Update a permission over an action
     *
     * @param Action $action
     * @param bool $permission
     */
    public function updatePermission(Action $action, bool $permission)
    {
        $this->actions()->sync([$action->id => ['permission' => $permission]], false);
        $this->refresh();
    }

    /**
     * Update multiple permissions. If detaching is true, then previous permissions are removed.
     * The array format must be:
     *
     *  [
     *      $actionId => true|false,
     *      ...
     *  ]
     *
     * @param array $permissions
     * @param bool $detaching
     */
    public function bulkPermissions(array $permissions, bool $detaching = false)
    {
        $permissions = array_map(function ($item) {
            return ['permission' => $item];
        }, $permissions);

        $this->actions()->sync($permissions, $detaching);
    }

    /**
     * Returns whether a permission is granted.
     *
     * @param Action $action
     * @return bool
     */
    public function hasPermission(Action $action)
    {
        if ($this->shouldGrantAllPermissions()) {
            return true;
        }

        if (!is_null($permission = $this->actions()->find($action->id))) {
            return $permission->pivot->permission;
        }

        return false;
    }

    /**
     * Return whether should grant all permissions
     *
     * @return bool
     */
    private function shouldGrantAllPermissions()
    {
        if (method_exists($this, 'grantAllPermissions')) {
            return $this->grantAllPermissions();
        }
        return false;
    }
}