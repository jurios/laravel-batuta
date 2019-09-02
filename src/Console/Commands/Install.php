<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;
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

        ProgressBar::setFormatDefinition('custom',
            ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% %message%'
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->title('Installing Batuta');
        $this->output->writeln('<fg=blue;options=bold>Publishing migrations:<fg=default>');
        $this->publishMigrations();
        $this->output->writeln('<fg=green;options=bold>Batuta successfully installed<fg=default>');
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

        $progressBar = new ProgressBar($this->output, count($migrations));
        $progressBar->setMessage('Publishing migrations...');
        $progressBar->setFormat('custom');
        $progressBar->start();

        /** @var SplFileInfo $migration */
        foreach ($migrations as $migration) {
            $filename = $migration->getFilename();

            if (preg_match('/\.php\.stub$/', $filename)) {
                $name = preg_replace('/\.stub$/', '', $migration->getFilename());
                $name = preg_replace('/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}_/', '', $name);

                $progressBar->setMessage('Publishing migration: ' . $name);

                $this->filesystem->copy($migration->getRealPath(), database_path('migrations/'.date('Y_m_d_His', time()). '_' . $name));

                // Sleep must be performed in order to change the datetime in the generated migration file name
                sleep(1);
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->output->writeln('');

        return $publish;
    }
}