<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Kodilab\LaravelBatuta\Builder\BatutaBuilder;
use Kodilab\LaravelBatuta\Exceptions\ActionAlreadyExists;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Tests\TestCase;

class BuildsActionsTest extends TestCase
{
    use WithFaker;

    public function test_createAction_should_create_an_action()
    {
        $action = $this->faker->unique()->word;
        $resource = $this->faker->unique()->word;

        BatutaBuilder::createAction($action, $resource);

        $this->assertTrue(Action::where('verb', Str::slug($action))->where('resource', Str::slug($resource))->get()->isNotEmpty());
    }

    public function test_createAction_should_generate_the_name()
    {
        $verb = $this->faker->unique()->word;
        $resource = $this->faker->unique()->word;

        BatutaBuilder::createAction($verb, $resource);

        $action = Action::where('verb', $verb)->where('resource', $resource)->get()->first();

        $this->assertEquals(Str::slug($verb) . ' ' . Str::slug($resource), $action->name);
    }

    public function test_createAction_name_and_resource_is_slugged()
    {
        $verb = mb_strtoupper($this->faker->unique()->word);
        $resource = mb_strtoupper($this->faker->unique()->word);

        BatutaBuilder::createAction($verb, $resource);

        $action = Action::where('verb', Str::slug($verb))->where('resource', Str::slug($resource))->get()->first();

        $this->assertEquals(Str::slug($verb), $action->verb);
        $this->assertEquals(Str::slug($resource), $action->resource);
    }

    public function test_createAction_should_throw_an_exception_if_the_action_already_exists()
    {
        $this->expectException(ActionAlreadyExists::class);

        $action = $this->faker->unique()->word;
        $resource = $this->faker->unique()->word;

        BatutaBuilder::createAction($action, $resource);
        BatutaBuilder::createAction($action, $resource);
    }

    public function test_removeAction_should_remove_an_action_to_the_resource()
    {
        $action = factory(Action::class)->create();

        BatutaBuilder::removeAction($action->verb, $action->resource);

        $this->assertNull(Action::findByName($action->name));
    }
}