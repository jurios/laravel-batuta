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