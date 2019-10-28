<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Testing\Traits\InstallPackage;
use Kodilab\LaravelBatuta\Tests\TestCase;

class ActionTest extends TestCase
{
    use WithFaker;
    use InstallPackage;

    public function test_persisted_verb_should_slug_the_verb()
    {
        $verb = mb_strtoupper($this->faker->word) . " " . mb_strtoupper($this->faker->word);

        $action = factory(Action::class)->create(['verb' => $verb]);

        $this->assertEquals(Str::slug($verb), $action->verb);
    }

    public function test_persisted_resource_should_slug_the_resource()
    {
        $resource = mb_strtoupper($this->faker->word) . " " . mb_strtoupper($this->faker->word);

        $action = factory(Action::class)->create(['resource' => $resource]);

        $this->assertEquals(Str::slug($resource), $action->resource);
    }

    public function test_name_should_be_generated_on_save()
    {
        $verb = $this->faker->unique()->word;
        $resource = $this->faker->unique()->word;

        $action = factory(Action::class)->create(compact('verb', 'resource'));

        $this->assertEquals($verb . ' ' . $resource, $action->name);
    }

    public function test_findByName_should_return_the_resource_which_name_is_equal()
    {
        $action = factory(Action::class)->create();

        $this->assertTrue($action->is(Action::findByName($action->name)));
    }

    public function test_findByName_should_return_null_if_the_action_does_not_exists()
    {
        $this->assertNull(Action::findByName($this->faker->word . ' ' . $this->faker->word));
    }
}