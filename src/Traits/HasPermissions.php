<?php


namespace Kodilab\LaravelBatuta\Traits;


use Kodilab\LaravelBatuta\Contracts\Permissionable;
use Kodilab\LaravelBatuta\Exceptions\ActionNotFound;
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
            ->using(Permission::class)->withPivot(['granted']);
    }

    /**
     * Update a permission over an action. Both, action instance or action name are allowed as input.
     *
     * @param mixed $action
     * @param bool $grant
     */
    public function updatePermission($action, bool $grant)
    {
        $action = $this->getActionInstanceOrFail($action);

        $this->batuta_actions()->sync([$action->id => ['granted' => $grant]], false);
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
            return ['granted' => $item];
        }, $permissions);

        $this->batuta_actions()->sync($permissions, $detaching);
    }

    /**
     * Returns whether a permission is granted. Both action instance or action name are allowed as input.
     *
     * @param mixed $action
     * @return bool
     */
    public function hasPermission($action)
    {
        $action = $this->getActionInstanceOrFail($action);

        if (config('batuta.allow_god', true) && $this->isGod()) {
            return true;
        }

        if (!is_null($permission = $this->batuta_actions()->find($action->id))) {
            return $permission->pivot->granted;
        }

        if ($this->shouldInheritPermissions()) {
            return method_exists($this, 'getInheritedPermission') ?
                $this->getInheritedPermission($action) : false;
        }

        return false;
    }

    /**
     * Returns if role inheritance is allowed for that actor
     *
     * @return bool
     */
    protected function shouldInheritPermissions()
    {
       return config('batuta.role_inheritance', true);
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

    /**
     * Returns the persisted action. If action is the action name, look for the action.
     *
     * @param $action
     * @return Action
     */
    private function getActionInstanceOrFail($action)
    {
        if (is_string($action) && !is_null($action_instance = Action::findByName($action))) {
            $action = $action_instance;
        }

        if (!is_string($action) && get_class($action) === Action::class && $action->exists) {
            return $action;
        }

        throw new ActionNotFound(sprintf('Action \'%s\' not found', $action));
    }
}