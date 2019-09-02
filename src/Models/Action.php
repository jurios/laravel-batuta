<?php

namespace Kodilab\LaravelBatuta\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Action extends Model
{
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('batuta.tables.actions', 'batuta_actions');
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        self::saving(function (Action $resource) {
            $resource->name = Str::slug($resource->name);
            return $resource;
        });
    }
}