<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Commands;


use Illuminate\Filesystem\Filesystem;
use Kodilab\LaravelBatuta\Tests\TestCase;

class InstallTest extends TestCase
{
    /** @var Filesystem */
    protected $filesystem;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->removeCopiedMigrations();

        $this->filesystem = new Filesystem();
    }

    protected function tearDown(): void
    {
        $this->removeCopiedMigrations();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function test_install_should_generate_migration_files()
    {
        $files = $this->filesystem->files(database_path('migrations'));

        $this->assertEquals(0, count($files));

        $this->artisan('make:batuta')->run();

        $files = $this->filesystem->files(database_path('migrations'));

        $this->assertNotEquals(0, count($files));
    }

    private function removeCopiedMigrations()
    {
        $this->filesystem->deleteDirectory(database_path('migrations'), true);
    }
}