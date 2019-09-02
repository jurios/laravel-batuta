<?php


namespace Kodilab\LaravelBatuta\Traits;


use Kodilab\LaravelBatuta\Models\Action;
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
}