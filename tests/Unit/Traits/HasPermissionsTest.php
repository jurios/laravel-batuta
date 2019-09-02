<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\TestCase;

class HasPermissionsTest extends TestCase
{
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
}