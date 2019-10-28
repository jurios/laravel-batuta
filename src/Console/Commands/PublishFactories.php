<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


class PublishFactories extends PublishCommand
{
    protected $signature = 'batuta:factories';

    protected $description = 'Publish the factory files';

    protected $factories_path = __DIR__ . '/../../../../database/factories';

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
        $index = 0;
        $files = $this->filesystem->allFiles($this->factories_path);

        foreach ($files as $file) {
            $destination = database_path('factories') . DIRECTORY_SEPARATOR . $file->getFilename();

            $destinations[$file->getRealPath()] = $destination;

            $index++;
        }

        return $destinations;
    }
}