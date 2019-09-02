<?php

namespace Kodilab\LaravelBatuta\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resource extends Model
{
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('batuta.tables.resources', 'perm_resources');
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        self::saving(function (Resource $resource) {
            $resource->name = Str::slug($resource->name);
            return $resource;
        });
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}