<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\fixtures\Models\User;
use Kodilab\LaravelBatuta\Tests\TestCase;

class UserPermissionsTest extends TestCase
{
    public function test_updateRoles_should_add_the_role_to_the_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertNull($user->roles()->find($role->id));

        $user->updateRoles([$role->id]);

        $this->assertNotNull($user->roles()->find($role->id));
    }

    public function test_addRole_should_add_the_role_to_the_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertNull($user->roles()->find($role->id));

        $user->addRole($role);

        $this->assertNotNull($user->roles()->find($role->id));
    }

    public function test_addRole_should_not_remove_the_previous_role_associations()
    {
        $user = factory(User::class)->create();
        $previous_role = factory(Role::class)->create();
        $role = factory(Role::class)->create();

        $user->addRole($previous_role);

        $this->assertNotNull($user->roles()->find($previous_role->id));

        $user->addRole($role);

        $this->assertNotNull($user->roles()->find($previous_role->id));
        $this->assertNotNull($user->roles()->find($role->id));
    }

    public function test_isGod_should_return_false_if_the_user_does_not_have_a_god_role()
    {
        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();

        $user->addRole($role);

        $this->assertFalse($user->isGod());
    }

    public function test_isGod_should_return_true_if_the_user_have_a_god_role()
    {
        $user = factory(User::class)->create();
        $god_role = Role::where('god', true)->get()->first();
        $role = factory(Role::class)->create();

        $user->addRole($role);
        $this->assertFalse($user->isGod());

        $user->addRole($god_role);
        $this->assertTrue($user->isGod());
    }

    public function test_hasRole_should_return_true_if_the_user_has_the_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertFalse($user->hasRole($role));

        $user->addRole($role);

        $this->assertTrue($user->hasRole($role));
    }

    public function test_removeRole_should_remove_a_give_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->updateRoles([$role->id, $role2->id]);

        $this->assertTrue($user->hasRole($role));
        $this->assertTrue($user->hasRole($role2));

        $user->removeRole($role2);

        $this->assertTrue($user->hasRole($role));
        $this->assertFalse($user->hasRole($role2));
    }

    public function test_removeAllRoles_should_remove_all_roles_associated()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->updateRoles([$role->id, $role2->id]);

        $this->assertTrue($user->hasRole($role));
        $this->assertTrue($user->hasRole($role2));

        $user->removeAllRoles();

        $this->assertFalse($user->hasRole($role));
        $this->assertFalse($user->hasRole($role2));
    }

    public function test_hasPermission_should_return_false_if_a_user_permission_is_not_set()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $this->assertFalse($user->hasPermission($action));
    }

    public function test_hasPermission_should_return_false_if_a_user_permission_is_set_to_false()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $user->updatePermissions([$action->id => false]);

        $this->assertFalse($user->hasPermission($action));
    }

    public function test_hasPermission_should_return_true_if_a_user_permission_is_set_to_true()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $user->updatePermissions([$action->id => true]);

        $this->assertTrue($user->hasPermission($action));
    }

    public function test_hasPermission_should_return_the_role_permission_if_the_user_permission_is_not_set()
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

    public function test_god_user_hasPermission_should_return_true()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $god_role = Role::god();

        $action = factory(Action::class)->create();

        $user->addRole($god_role);

        $user->updatePermissions([$action->id => false]);

        $this->assertTrue($user->hasPermission($action));
    }
}