<?php

namespace Kodilab\LaravelBatuta\Tests;

use Illuminate\Encryption\Encrypter;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Exceptions\Handler;
use Kodilab\LaravelBatuta\LaravelBatutaProvider;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Finder\SplFileInfo;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $filesystem;

    protected $resources_path = __DIR__ . DIRECTORY_SEPARATOR . 'resources';

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->filesystem = new Filesystem();

        $this->createResourcesDirectory();
        $this->copyMigrations();
    }

    public function __destruct()
    {
        $this->removeResourcesDirectory();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom($this->resources_path . DIRECTORY_SEPARATOR . 'database/migrations');

        $this->artisan('migrate')->run();

        $this->withFactories(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'database/factories');
        $this->withFactories(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fixtures/database/factories');

        $this->app['config']->set('app.key', 'base64:' . base64_encode(
            Encrypter::generateKey($this->app['config']->get('app.cipher'))
        ));
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelBatutaProvider::class
        ];
    }

    /**
     * Copy migration files into the laravel instance for testing. (The provider does not load the migrations so
     * this must be done manually)
     */
    private function copyMigrations()
    {
        $this->clearMigrationsDirectory();

        $this->filesystem->makeDirectory($this->resources_path . DIRECTORY_SEPARATOR . 'database/migrations', 0755, true);

        $migrations = $this->filesystem->files(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'database/migrations');

        /** @var SplFileInfo $migration */
        foreach ($migrations as $migration)
        {
            // removes the .stub from the file
            $filename = preg_replace('/\.stub$/', '', $migration->getFilename());
            $filename = preg_replace('/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}/', '', $filename);

            $this->filesystem->copy(
                $migration->getRealPath(),
                $this->resources_path . DIRECTORY_SEPARATOR . 'database/migrations/'.date('Y_m_d_His', time()). $filename
            );
        }
    }

    /**
     * Removes migration from the laravel instance
     */
    private function clearMigrationsDirectory()
    {
        $this->filesystem->deleteDirectory($this->resources_path . DIRECTORY_SEPARATOR . 'database');
    }

    private function createResourcesDirectory()
    {
        if (!is_dir($this->resources_path)) {
            $this->filesystem->makeDirectory($this->resources_path, 0755, true);
        }
    }

    private function removeResourcesDirectory()
    {
        if(is_dir($this->resources_path)) {
            $this->filesystem->deleteDirectory($this->resources_path);
        }
    }
}