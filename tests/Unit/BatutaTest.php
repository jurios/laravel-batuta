<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\WithFaker;
use Kodilab\LaravelBatuta\Batuta\Batuta;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Resource;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\TestCase;

class BatutaTest extends TestCase
{
    use WithFaker;

    /*
     * RESOURCES
     */
    public function test_createResource_should_create_a_resource()
    {
        $this->assertTrue(Resource::all()->isEmpty());

        Batuta::createResource($this->faker->word);

        $this->assertFalse(Resource::all()->isEmpty());
    }

    public function test_createResource_should_throw_an_exception_if_the_resource_name_is_already_exists()
    {
        $this->expectException(QueryException::class);
        $resource = factory(Resource::class)->create();

        Batuta::createResource($resource->name);
    }

    public function test_removeResource_should_remove_the_resource()
    {
        $resource = factory(Resource::class)->create();

        Batuta::removeResource($resource->name);

        $this->assertNull(Resource::find($resource->id));
    }

    public function test_removeResource_which_does_not_exists_should_do_nothing()
    {
        $resource = factory(Resource::class)->create();

        Batuta::removeResource($this->faker->unique()->name);

        $this->assertNotNull(Resource::find($resource->id));
    }

    /*
     * ACTIONS
     */
    public function test_addActionToResource_should_add_an_action_to_the_resource()
    {
        $resource = factory(Resource::class)->create();

        $this->assertTrue($resource->actions->isEmpty());

        Batuta::addAction($resource->name, $this->faker->unique()->word);
        $resource->refresh();

        $this->assertFalse($resource->actions->isEmpty());
    }

    public function test_addActionToResource_should_throw_an_exception_if_the_resource_does_not_exists()
    {
        $this->expectException(ModelNotFoundException::class);
        Batuta::addAction($this->faker->unique()->word, $this->faker->unique()->word);
    }

    public function test_removeAction_should_remove_an_action_to_the_resource()
    {
        $resource = factory(Resource::class)->create();
        $action = factory(Action::class)->create([
            'resource_id' => $resource->id
        ]);

        $this->assertFalse($resource->actions->isEmpty());

        Batuta::removeAction($resource->name, $action->name);
        $resource->refresh();

        $this->assertTrue($resource->actions->isEmpty());
    }

    public function test_removeAction_should_throw_an_exception_if_the_resource_does_not_exists()
    {
        $this->expectException(ModelNotFoundException::class);
        Batuta::addAction($this->faker->unique()->word, $this->faker->unique()->word);
    }

    /*
     * ROLES
     */
    public function test_createRole_should_create_a_role()
    {
        $name = $this->faker->word;

        $this->assertTrue(Role::where('name', $name)->get()->isEmpty());

        Batuta::createRole($name);

        $this->assertFalse(Role::where('name', $name)->get()->isEmpty());
    }

    public function test_createRole_should_throw_an_exception_if_the_role_name_is_already_exists()
    {
        $this->expectException(QueryException::class);
        $role = factory(Role::class)->create();

        Batuta::createRole($role->name);
    }

    public function test_removeRole_should_remove_the_role()
    {
        $role = factory(Role::class)->create();

        Batuta::removeRole($role->name);

        $this->assertNull(Role::find($role->id));
    }

    public function test_removeRole_which_does_not_exists_should_do_nothing()
    {
        $role = factory(Role::class)->create();

        Batuta::removeRole($this->faker->unique()->name);

        $this->assertNotNull(Role::find($role->id));
    }
}