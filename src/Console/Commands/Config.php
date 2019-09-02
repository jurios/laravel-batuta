<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Config extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batuta:config
                                {--force : Overwrite existing items by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Batuta configuration';

    /** @var Filesystem */
    protected $filesystem;

    protected $config_path = __DIR__.'/../../../config/config.php';

    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->title('Publishing Batuta configuration');

        if (!$this->isAlreadyPublished()) {
            $this->publishConfiguration();
            return;
        }

        if ($this->isAlreadyPublished() && $this->option('force')) {
            $this->publishConfiguration();
            return;
        }

        if ($this->confirm("The batuta configuration file already exists. Do you want to replace it?")) {
            $this->publishConfiguration();
            return;
        }
    }

    private function publishConfiguration()
    {
        $this->output->write('<fg=blue;options=bold>Publishing batuta.php file...');

        $this->filesystem->copy($this->config_path, config_path('batuta.php'));

        $this->output->writeln('<fg=green;options=bold>Done<fg=default>');
    }

    private function isAlreadyPublished()
    {
        return $this->filesystem->exists(config_path('batuta.php'));
    }
}