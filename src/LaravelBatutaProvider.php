<?php


namespace Kodilab\LaravelBatuta;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
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
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('permissions.php'),
        ], 'config');

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
        //
    }
}