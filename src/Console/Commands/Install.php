<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:batuta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install laravel-batuta package';

    /** @var Filesystem */
    protected $filesystem;

    protected $migrations_path = __DIR__.'/../../../database/migrations';

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
        $this->output->title('Installing Batuta');
        $this->output->write('<fg=blue;options=bold>Publishing migrations...');
        $this->publishMigrations();
        $this->output->writeln('<fg=green;options=bold>Done<fg=default>');
    }

    /**
     * Generates dynamically the migrations and copy them into the migration folder.
     *
     * @return array
     */
    private function publishMigrations()
    {
        $migrations = $this->filesystem->files($this->migrations_path);
        $publish = [];

        /** @var SplFileInfo $migration */
        foreach ($migrations as $migration) {
            $filename = $migration->getFilename();

            if (preg_match('/\.php\.stub$/', $filename)) {
                $name = preg_replace('/\.stub$/', '', $migration->getFilename());
                $name = preg_replace('/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}/', '', $name);

                $this->filesystem->copy($migration->getRealPath(), database_path('migrations/'.date('Y_m_d_His', time()). $name));
            }
        }

        return $publish;
    }
}