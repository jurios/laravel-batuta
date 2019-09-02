<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\fixtures\Models\User;
use Kodilab\LaravelBatuta\Tests\TestCase;

class HasPermissionsTest extends TestCase
{
    public function test_role_hasPermission_should_return_false_if_a_role_permission_is_not_set()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $this->assertFalse($role->hasPermission($action));
    }

    public function test_user_hasPermission_should_return_false_if_a_user_permission_is_not_set()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $this->assertFalse($user->hasPermission($action));
    }


    public function test_role_hasPermission_should_return_false_if_a_role_permission_is_set_to_false()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $role->updatePermissions([$action->id => false]);

        $this->assertFalse($role->hasPermission($action));
    }

    public function test_user_hasPermission_should_return_false_if_a_user_permission_is_set_to_false()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $user->updatePermissions([$action->id => false]);

        $this->assertFalse($user->hasPermission($action));
    }

    public function test_role_hasPermission_should_return_true_if_a_role_permission_is_set_to_true()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $role->updatePermissions([$action->id => true]);

        $this->assertTrue($role->hasPermission($action));
    }

    public function test_user_hasPermission_should_return_true_if_a_user_permission_is_set_to_true()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $user->updatePermissions([$action->id => true]);

        $this->assertTrue($user->hasPermission($action));
    }

    public function test_user_hasPermission_should_return_the_role_permission_if_the_user_permission_is_not_set()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $user->addRole($role);

        $role->updatePermissions([$action->id => true]);

        $this->assertTrue($user->hasPermission($action));

        $role->updatePermissions([$action->id => false]);

        $this->assertFalse($user->hasPermission($action));
    }

    public function test_role_updatePermissions_add_new_permissions_to_the_role()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $role->updatePermissions([$action->id => true]);

        $this->assertTrue($role->actions()->find($action->id)->pivot->permission);
    }

    public function test_role_updatePermissions_update_a_role_permission_to_the_role()
    {
        $role = factory(Role::class)->create();
        $action = factory(Action::class)->create();

        $role->updatePermissions([$action->id => true]);

        $this->assertTrue($role->actions()->find($action->id)->pivot->permission);

        $role->updatePermissions([$action->id => false]);

        $this->assertFalse($role->actions()->find($action->id)->pivot->permission);
    }

    public function test_role_updatePermissions_in_detaching_mode_should_remove_previous_permissions()
    {
        $role = factory(Role::class)->create();

        $action = factory(Action::class)->create();
        $action2 = factory(Action::class)->create();

        $role->updatePermissions([$action2->id => true]);

        $this->assertNotNull($role->actions()->find($action2->id));

        $role->updatePermissions([$action->id => false], true);

        $this->assertNull($role->actions()->find($action2->id));
    }

    public function test_role_god_role_should_has_permission_always()
    {
        /** @var Role $god_role */
        $god_role = Role::where('god', true)->get()->first();
        $action = factory(Action::class)->create();

        $this->assertTrue($god_role->hasPermission($action));

        $god_role->updatePermissions([$action->id => false]);

        $this->assertTrue($god_role->hasPermission($action));
    }
}