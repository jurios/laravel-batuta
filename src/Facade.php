<?php


namespace Kodilab\LaravelBatuta;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Create a resource
     *
     * @param string $name
     */
    public static function createResource(string $name)
    {
        DB::table(config('batuta.tables.resources', 'batuta_resources'))->insert([
            'name' => $name
        ]);
    }

    /**
     * Remove a resource based on its name or its id
     *
     * @param string $name
     */
    public static function removeResource(string $name)
    {
        DB::table(config('batuta.tables.resources', 'batuta_resources'))
            ->where('name', $name)->delete();
    }

    /**
     * Adds an actions to the given resource by its name
     *
     * @param string $resource_name
     * @param string $action_name
     * @param string|null $action_description
     */
    public static function addAction(string $resource_name, string $action_name, string $action_description = null)
    {
        $resource = self::getResourceOrFail($resource_name);

        DB::table(config('batuta.tables.actions', 'batuta_actions'))->insert([
            'name' => $action_name,
            'description' => $action_description,
            'resource_id' => $resource->id
        ]);
    }

    /**
     * Remove an action from the given resource by its name
     *
     * @param string $resource_name
     * @param string $action_name
     */
    public static function removeAction(string $resource_name, string $action_name)
    {
        $resource = self::getResourceOrFail($resource_name);

        DB::table(config('batuta.tables.actions', 'batuta_actions'))
            ->where('resource_id', $resource->id)->where('name', $action_name)
            ->delete();
    }

    /**
     * Create a role
     *
     * @param string $name
     */
    public static function createRole(string $name)
    {
        DB::table(config('batuta.tables.roles', 'batuta_roles'))->insert([
            'name' => $name
        ]);
    }

    /**
     * Remove a role given by its name
     *
     * @param string $name
     */
    public static function removeRole(string $name)
    {
        DB::table(config('batuta.tables.roles', 'batuta_roles'))
            ->where('name', $name)->delete();
    }

    /**
     * Returns the resource which name is the name given. If it does not exists, then a ModelNotFoundException is trown.
     *
     * @param string $resource_name
     * @return string
     */
    private static function getResourceOrFail(string $resource_name)
    {
        $resource = DB::table(config('batuta.tables.resources', 'batuta_resources'))
            ->where('name', $resource_name)->get()->first();

        if (is_null($resource)) {
            throw new ModelNotFoundException('Resource not found which name=' . $resource_name);
        }

        return $resource;
    }

    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}