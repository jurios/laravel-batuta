<?php


namespace Kodilab\LaravelBatuta\Contracts;


use Kodilab\LaravelBatuta\Models\Action;

interface Permissionable
{
    /**
     * Returns the permissions table (many to many relationship with actions)
     * @return mixed
     */
    public function getPermissionsTable();

    /**
     * Updates a permission (or create if it does not exist). If detaching is true, then the previous permissions are
     * removed
     *
     * @param array $permissions
     * @param bool $detaching
     * @return mixed
     */
    public function updatePermission(Action $action, bool $detaching);

    /**
     * Updates multiple permissions
     *
     * @param array $permissions
     * @param bool $detaching
     * @return mixed
     */
    public function bulkPermissions(array $permissions, bool $detaching = false);

    /**
     * Returns whether it has permission or not for the given action
     *
     * @param Action $action
     * @return bool
     */
    public function hasPermission(Action $action);

}