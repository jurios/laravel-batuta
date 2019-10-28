<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishConfig extends PublishCommand
{

    protected $signature = 'batuta:config';
    protected $description = 'Publish Batuta configuration';

    protected $config_path = __DIR__.'/../../../config';

    public function __construct()
    {
        parent::__construct();

        $this->destinations = $this->generateConfigDestination();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->publish();
    }

    /**
     * Returns the destinations array
     * @return array
     */
    protected function generateConfigDestination()
    {
        return [
            $this->config_path . DIRECTORY_SEPARATOR . 'config.php' => config_path('batuta.php')
        ];
    }
}