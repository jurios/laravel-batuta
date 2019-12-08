<?php


namespace Kodilab\LaravelBatuta\Support\Facades;


use Illuminate\Support\Facades\Route;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'batuta';
    }

    public static function godRegistration(string $controller = 'Auth\RegisterController')
    {
        Route::prefix('god')->group(function () use ($controller) {
            Route::post('register', $controller . '@register_god')->name('register.god');
        });
    }
}