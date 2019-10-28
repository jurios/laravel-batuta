<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


class PublishMigrations extends PublishCommand
{
    protected $signature = 'batuta:migrations';

    protected $description = 'Publish the migration files';

    protected $migrations_path = __DIR__ . '/../../../database/migrations';

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
        $files = $this->getStubFilesFrom($this->migrations_path);

        foreach ($files as $file) {
            $name = $this->removeStubExtension($file->getFilename());
            $name = preg_replace('/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}_/', '', $name);
            $destination = database_path('migrations') . DIRECTORY_SEPARATOR . $this->generateMigrationFilename($name, $index);

            $destinations[$file->getRealPath()] = $destination;

            $index++;
        }

        return $destinations;
    }

    /**
     * Generates the migration filename based on the timestamp
     *
     * @param string $name
     * @param int $index
     * @return string
     */
    protected function generateMigrationFilename(string $name, int $index)
    {
        return date('Y_m_d_His', time() + $index). '_' . $name;
    }
}