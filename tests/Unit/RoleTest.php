<?php


namespace Kodilab\LaravelBatuta\Tests\Unit;


use Illuminate\Support\Facades\DB;
use Kodilab\LaravelBatuta\Exceptions\DefaultRoleNotFound;
use Kodilab\LaravelBatuta\Exceptions\GodRoleNotFound;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Testing\Traits\InstallPackage;
use Kodilab\LaravelBatuta\Tests\TestCase;

class RoleTest extends TestCase
{
    use InstallPackage;

    public function test_default_role_can_not_be_destroyed_using_the_model()
    {
        $this->expectException(\InvalidArgumentException::class);

        $role = factory(Role::class)->create([
            'default' => true
        ])->delete();

    }

    public function test_god_role_can_not_be_destroyed_using_the_model()
    {
        $this->expectException(\InvalidArgumentException::class);

        $role = factory(Role::class)->create([
            'god' => true
        ])->delete();
    }

    public function test_isDefault_returns_whether_a_role_is_default()
    {
        $role = factory(Role::class)->create(['default' => true]);
        $this->assertTrue($role->isDefault());
    }

    public function test_isDefault_should_return_false_if_the_role_is_not_default()
    {
        $role = factory(Role::class)->create();
        $this->assertFalse($role->isDefault());
    }

    public function test_getDefault_should_return_the_default_role()
    {
        $default = factory(Role::class)->create([
            'default' => true
        ]);

        $this->assertTrue($default->is(Role::getDefault()));
    }

    public function test_persisting_a_new_default_role_should_remove_the_previous_default_role_flag()
    {
        $default_role = Role::getDefault();

        $role = factory(Role::class)->create([
            'default' => true
        ]);

        $this->assertFalse($default_role->is(Role::getDefault()));
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

    public function test_getGod_should_return_null_if_a_god_role_does_not_exist()
    {
        DB::table(config('batuta.tables.roles', 'roles'))->where('god', true)->delete();

        $this->assertNull(Role::getGod());
    }
}