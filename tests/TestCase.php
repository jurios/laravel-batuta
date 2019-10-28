<?php

namespace Kodilab\LaravelBatuta\Tests;

use Illuminate\Encryption\Encrypter;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Exceptions\Handler;
use Kodilab\LaravelBatuta\LaravelBatutaProvider;
use Kodilab\LaravelBatuta\Testing\Traits\InstallPackage;
use Kodilab\LaravelBatuta\Testing\Traits\LaravelOperations;
use Kodilab\LaravelBatuta\Testing\Traits\MigratePackage;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Finder\SplFileInfo;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use LaravelOperations;

    protected $filesystem;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->filesystem = new Filesystem();

        $this->removePublishedMigrations();
    }

    public function __destruct()
    {
        $this->removePublishedMigrations();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->app['config']->set('app.key', 'base64:' . base64_encode(
            Encrypter::generateKey($this->app['config']->get('app.cipher'))
        ));

        $this->artisan('migrate')->run();
    }

    /**
     * Add custom Boot helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();
        if (isset($uses[MigratePackage::class])) {
            $this->migratePackageSetUp();
        }
        if (isset($uses[InstallPackage::class])) {
            $this->installPackageSetUp();
        }
        return $uses;
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelBatutaProvider::class
        ];
    }
}