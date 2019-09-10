<?php

namespace Kodilab\LaravelBatuta\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Action extends Model
{
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('batuta.tables.actions', 'actions');
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        self::saving(function (Action $action) {
            $action->verb = Str::slug($action->verb);
            $action->resource = Str::slug($action->resource);
            $action->name = $action->verb . ' ' . $action->resource;
        });
    }

    /**
     * Returns an action by the name
     *
     * @param string $name
     * @return mixed
     */
    public static function findByName(string $name)
    {
        return self::where('name', $name)->get()->first();
    }
}