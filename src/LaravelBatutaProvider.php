<?php


namespace Kodilab\LaravelBatuta;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Kodilab\LaravelBatuta\Console\Commands\Config;
use Kodilab\LaravelBatuta\Console\Commands\PublishMigrations;

class LaravelBatutaProvider extends ServiceProvider
{
    /** @var Filesystem */
    protected $filesystem;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            Config::class,
            PublishMigrations::class
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}