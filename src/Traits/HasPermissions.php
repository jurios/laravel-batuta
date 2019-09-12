<?php


namespace Kodilab\LaravelBatuta\Traits;


use Kodilab\LaravelBatuta\Contracts\Permissionable;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Pivots\Permission;

trait HasPermissions
{
    protected static function bootHasPermissions()
    {
        if (!in_array(Permissionable::class, class_implements(self::class))) {
            throw new \Exception(
                sprintf("Class '%s' does not implement '%s' interface when is using '%s' trait",
                    self::class, Permissionable::class, HasPermissions::class)
            );
        }
    }

    /**
     * Actions relationship (permissions)
     *
     * @return mixed
     */
    public function batuta_actions()
    {
        return $this->belongsToMany(Action::class, $this->getPermissionsTable())
            ->using(Permission::class)->withPivot(['permission']);
    }

    /**
     * Update a permission over an action
     *
     * @param Action $action
     * @param bool $permission
     */
    public function updatePermission(Action $action, bool $permission)
    {
        $this->batuta_actions()->sync([$action->id => ['permission' => $permission]], false);
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

        $this->batuta_actions()->sync($permissions, $detaching);
    }

    /**
     * Returns whether a permission is granted.
     *
     * @param Action $action
     * @return bool
     */
    public function hasPermission(Action $action)
    {
        if ($this->isGod()) {
            return true;
        }

        if (!is_null($permission = $this->batuta_actions()->find($action->id))) {
            return $permission->pivot->permission;
        }

        if (! method_exists($this, 'shouldInheritPermissions') || $this->shouldInheritPermissions()) {
            return method_exists($this, 'getInheritedPermission') ?
                $this->getInheritedPermission($action) : false;
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