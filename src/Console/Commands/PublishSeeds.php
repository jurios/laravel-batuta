<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


class PublishSeeds extends PublishCommand
{
    protected $signature = 'batuta:seeds';

    protected $description = 'Publish the seed files';

    protected $seeds_path = __DIR__ . '/../../../database/seeds';

    public function __construct()
    {
        parent::__construct();

        $this->destinations = $this->generateDestinationPaths();
    }

    public function handle()
    {
        $this->publish();
    }

    /**
     * Returns the destinations array
     * @return array
     */
    protected function generateDestinationPaths()
    {
        $destinations = [];
        $files = $this->getStubFilesFrom($this->seeds_path);

        foreach ($files as $file) {
            $name = $this->removeStubExtension($file->getFilename());
            $destination = database_path('seeds') . DIRECTORY_SEPARATOR . $name;

            $destinations[$file->getRealPath()] = $destination;
        }

        return $destinations;
    }
}