<?php


namespace Kodilab\LaravelBatuta\Builder\Traits;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Kodilab\LaravelBatuta\Exceptions\ActionAlreadyExists;

trait BuildsActions
{
    /**
     * Adds an actions to the given resource by its name
     *
     * @param string $verb
     * @param string $resource
     * @param string|null $description
     */
    public static function createAction(string $verb, string $resource, string $description = null)
    {
        $table = config('batuta.tables.actions', 'actions');

        if (DB::table($table)->where('verb', $verb)->where('resource', $resource)->get()->isNotEmpty()) {
            throw new ActionAlreadyExists($verb, $resource);
        }

        DB::table(config('batuta.tables.actions', 'actions'))->insert([
            'verb' => Str::slug($verb),
            'resource' => Str::slug($resource),
            'description' => $description,
            'name' => Str::slug($verb) . ' ' . Str::slug($resource)
        ]);
    }

    /**
     * Remove an action from the given resource by its name
     *
     * @param string $verb
     * @param string $resource
     */
    public static function removeAction(string $verb, string $resource = null)
    {
        DB::table(config('batuta.tables.actions', 'actions'))
            ->where('verb', $verb)->where('resource', $resource)
            ->delete();
    }
}