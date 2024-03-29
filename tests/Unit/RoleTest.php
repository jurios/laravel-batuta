<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Support\Facades\DB;
use Kodilab\LaravelBatuta\Exceptions\DefaultRoleNotFound;
use Kodilab\LaravelBatuta\Exceptions\GodRoleNotFound;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_default_role_can_not_be_destroyed()
    {
        $this->expectException(\InvalidArgumentException::class);

        Role::getDefault()->delete();

    }

    public function test_isDefault_returns_whether_a_role_is_default()
    {
        $this->assertTrue(Role::getDefault()->isDefault());

        $role = factory(Role::class)->create();

        $this->assertFalse($role->isDefault());
    }

    public function test_getDefault_should_return_the_default_role()
    {
        $default = Role::where('default', true)->get()->first();

        $this->assertTrue($default->is(Role::getDefault()));
    }

    public function test_getDefault_should_throw_an_exception_if_default_role_does_not_exist()
    {
        $this->expectException(DefaultRoleNotFound::class);

        DB::table(config('batuta.tables.roles', 'roles'))
            ->where('default', true)->delete();

        Role::getDefault();
    }

    public function test_getGod_should_return_the_god_role()
    {
        $god = Role::where('god', true)->get()->first();

        $this->assertTrue($god->is(Role::getGod()));
    }

    public function test_getGod_should_throw_an_exception_if_god_role_does_not_exists()
    {
        $this->expectException(GodRoleNotFound::class);

        DB::table(config('batuta.tables.roles', 'roles'))
            ->where('god', true)->delete();

        Role::getGod();
    }
}