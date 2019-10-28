<?php


namespace Kodilab\LaravelBatuta\Console\Commands\Traits;


use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

trait PublishStubFiles
{
    /**
     * @param string $origin
     * @param string $destination
     * @param array $replacements
     */
    protected function publishStubFile(string $origin, string $destination, array $replacements = []) : void
    {
        if (!$this->filesystem->isFile($origin)) {
            throw new FileNotFoundException($origin);
        }

        $content = str_replace(array_keys($replacements), $replacements, file_get_contents($origin));

        file_put_contents($destination, $content);
    }

    /**
     * @param string $origin
     * @param string $destination
     */
    protected function publishFile(string $origin, string $destination)
    {
        $this->publishStubFile($origin, $destination, []);
    }

    /**
     * Removes .stub extension from the string if .stub is present as extension
     *
     * @param string $filename
     *
     * @return mixed
     */
    protected function removeStubExtension(string $filename)
    {
        return preg_replace('/\.stub$/', '', $filename);
    }

    protected function getStubFilesFrom(string $path)
    {
        return array_filter($this->filesystem->allFiles($path), function (SplFileInfo $file) {
            return $file->getExtension() === 'stub';
        });
    }
}