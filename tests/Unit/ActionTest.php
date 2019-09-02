<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Tests\TestCase;

class ActionTest extends TestCase
{
    use WithFaker;

    public function test_persisted_resource_should_slug_the_name()
    {
        $name = mb_strtoupper($this->faker->word) . " " . mb_strtoupper($this->faker->word);

        $resource = factory(Action::class)->create(['name' => $name]);

        $this->assertEquals(Str::slug($name), $resource->name);
    }
}