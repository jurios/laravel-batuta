<?php


namespace Kodilab\LaravelBatuta\Pivots;


use Illuminate\Database\Eloquent\Relations\Pivot;

class Permission extends Pivot
{
    protected $casts = [
        'granted' => 'boolean'
    ];
}