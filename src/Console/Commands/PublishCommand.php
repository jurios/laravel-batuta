<?php


namespace Kodilab\LaravelBatuta\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Kodilab\LaravelBatuta\Console\Commands\Traits\PublishStubFiles;

abstract class PublishCommand extends Command
{
    use PublishStubFiles;

    /** @var string[] */
    protected $destinations;

    /** @var string[] */
    protected $replacements;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
        $this->destinations = [];
        $this->replacements = [];
    }

    protected function publish()
    {
        foreach ($this->destinations as $original => $destination)
        {
            $this->publishStubFile($original, $destination, $this->replacements);
            $this->output->writeln("<fg=cyan>$destination <fg=green>published.</>");
        }
    }

}