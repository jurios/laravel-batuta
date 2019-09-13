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
     * @param $action
     * @param bool $grant
     * @return mixed
     */
    public function updatePermission($action, bool $grant);

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
     * @param mixed $action
     * @return bool
     */
    public function hasPermission($action);

}