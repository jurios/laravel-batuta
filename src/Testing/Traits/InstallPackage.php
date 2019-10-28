<?php


namespace Kodilab\LaravelBatuta\Testing\Traits;


trait InstallPackage
{
    use MigratePackage;

    /**
     * Install the package
     *
     */
    public function installPackageSetUp()
    {
        $this->withFactories(__DIR__ . '/../../../database/factories');
        $this->withFactories(__DIR__ . '/../../../tests/fixtures/database/factories');
    }
}