<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\WithFaker;
use Kodilab\LaravelBatuta\Builder\BatutaBuilder;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\TestCase;

class BuildsRolesTest extends TestCase
{
    use WithFaker;

    public function test_createRole_should_create_a_role()
    {
        $name = $this->faker->word;
        $this->assertTrue(Role::where('name', $name)->get()->isEmpty());

        BatutaBuilder::createRole($name);

        $this->assertFalse(Role::where('name', $name)->get()->isEmpty());
    }

    public function test_createRole_should_throw_an_exception_if_the_role_name_is_already_exists()
    {
        $this->expectException(QueryException::class);

        $role = factory(Role::class)->create();

        BatutaBuilder::createRole($role->name);
    }

    public function test_removeRole_should_remove_the_role()
    {
        $role = factory(Role::class)->create();

        BatutaBuilder::removeRole($role->name);

        $this->assertNull(Role::find($role->id));
    }

    public function test_removeRole_which_does_not_exists_should_do_nothing()
    {
        $role = factory(Role::class)->create();

        BatutaBuilder::removeRole($this->faker->unique()->name);

        $this->assertNotNull(Role::find($role->id));
    }
}