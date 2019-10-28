<?php


namespace Kodilab\LaravelBatuta\Testing\Traits;


use Kodilab\LaravelBatuta\Seeders\RoleTableSeeder;
use Symfony\Component\Finder\SplFileInfo;

trait MigratePackage
{
    /**
     * Install the package
     *
     */
    public function migratePackageSetUp()
    {
        $this->withFactories(__DIR__ . '/../../database/factories');

        if (!$this->filesystem->isDirectory(database_path('seeds'))) {
            $this->filesystem->makeDirectory(database_path('seeds'));
        }

        //Only publish package migrations in case other test hasn't done it yet.
        if (!$this->areMigrationsPublished()) {
            $this->artisan('batuta:migrations');
            $this->artisan('batuta:seeds');
        }

        $this->loadLaravelMigrations();
        $this->artisan('migrate');

        try {
            $this->artisan('db:seed', [
                '--class' => RoleTableSeeder::class
            ]);
        } catch (\Exception $e) {}
    }

    /**
     * Detects whether other test has published the migrations before.
     *
     * @return bool
     */
    private function areMigrationsPublished()
    {
        $exists = false;

        $stubs = array_filter(
            $this->filesystem->files(__DIR__ . '/../../../database/migrations'),
            function (SplFileInfo $file) {
                return $file->getExtension() === 'stub';
            }
        );

        $migrations = array_filter(
            $this->filesystem->files(database_path('migrations')), function (SplFileInfo $file) {
            return $file->getExtension() === 'php';
        }
        );

        $migrations = array_map(function (SplFileInfo $item) {
            return preg_replace('/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}/', '', $item->getFilename());
        }, $migrations);

        $stubs = array_map(function (SplFileInfo $item) {
            return preg_replace('/\.stub$/', '', preg_replace(
                    '/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}/', '', $item->getFilename())
            );
        }, $stubs);

        foreach ($stubs as $stub) {
            if (in_array($stub, $migrations)) {
                $exists = true;
            }
        }

        return $exists;
    }
}