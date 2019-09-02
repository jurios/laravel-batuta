<?php


namespace Kodilab\LaravelBatuta;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Kodilab\LaravelBatuta\Batuta\Batuta;
use Kodilab\LaravelBatuta\Console\Commands\Config;
use Kodilab\LaravelBatuta\Console\Commands\Install;

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

    public function __construct($app)
    {
        parent::__construct($app);

        $this->filesystem = new Filesystem();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            Install::class,
            Config::class
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('batuta', function ($app) {
            return new Batuta();
        });
    }
}