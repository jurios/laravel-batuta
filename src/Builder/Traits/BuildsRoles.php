<?php


namespace Kodilab\LaravelBatuta\Builder\Traits;


use Illuminate\Support\Facades\DB;

trait BuildsRoles
{
    /**
     * Create a role
     *
     * @param string $name
     */
    public static function createRole(string $name)
    {
        DB::table(config('batuta.tables.roles', 'roles'))->insert([
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
        DB::table(config('batuta.tables.roles', 'roles'))
            ->where('name', $name)->delete();
    }
}