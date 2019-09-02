<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    public function test_hasPermission_should_return_false_if_a_role_permission_is_not_set()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $this->assertFalse($role->hasPermission($action));
    }


    public function test_hasPermission_should_return_false_if_a_role_permission_is_set_to_false()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $role->updatePermissions([$action->id => false]);

        $this->assertFalse($role->hasPermission($action));
    }

    public function test_hasPermission_should_return_true_if_a_role_permission_is_set_to_true()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $role->updatePermissions([$action->id => true]);

        $this->assertTrue($role->hasPermission($action));
    }

    public function test_god_role_should_has_permission_in_any_case()
    {
        /** @var Role $god_role */
        $god_role = Role::where('god', true)->get()->first();
        $action = factory(Action::class)->create();

        $this->assertTrue($god_role->hasPermission($action));

        $god_role->updatePermissions([$action->id => false]);

        $this->assertTrue($god_role->hasPermission($action));
    }
}