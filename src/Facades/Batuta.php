<?php


namespace Kodilab\LaravelBatuta\Facades;


class Batuta extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'batuta';
    }
}