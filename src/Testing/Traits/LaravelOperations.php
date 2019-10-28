<?php


namespace Kodilab\LaravelBatuta\Testing\Traits;


trait LaravelOperations
{
    /**
     * Removes generated migrations from the Laravel instance
     */
    protected function removePublishedMigrations()
    {
        $path = __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel/database/migrations';

        if ($this->filesystem->isDirectory($path)) {
            $files = $this->filesystem->files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $this->filesystem->delete($file->getRealPath());
                }
            }
        }
    }

    /**
     * Removes generated factories from the Laravel instance
     */
    protected function removePublishedFactories()
    {
        foreach ($this->filesystem->files(database_path('factories')) as $file) {
            if ($file->getExtension() === 'php') {
                $this->filesystem->delete($file->getRealPath());
            }
        }
    }

    /**
     * Removes generated factories from the Laravel instance
     */
    protected function removePublishedSeeds()
    {
        if ($this->filesystem->isDirectory(database_path('seeds'))) {
            foreach ($this->filesystem->files(database_path('seeds')) as $file) {
                $this->filesystem->delete($file->getRealPath());
            }
        }
    }
}