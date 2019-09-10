<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Traits;


use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Tests\fixtures\Models\User;
use Kodilab\LaravelBatuta\Tests\TestCase;

class HasPermissionsTest extends TestCase
{
    public function test_updatePermissions_add_new_permissions()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $user->updatePermissions([$action->id => true]);

        $this->assertTrue($user->actions()->find($action->id)->pivot->permission);
    }

    public function test_role_updatePermissions_update_a_role_permission_to_the_role()
    {
        $user = factory(User::class)->create();
        $action = factory(Action::class)->create();

        $user->updatePermissions([$action->id => true]);

        $this->assertTrue($user->actions()->find($action->id)->pivot->permission);

        $user->updatePermissions([$action->id => false]);

        $this->assertFalse($user->actions()->find($action->id)->pivot->permission);
    }

    public function test_role_updatePermissions_in_detaching_mode_should_remove_previous_permissions()
    {
        $user = factory(User::class)->create();

        $action = factory(Action::class)->create();
        $action2 = factory(Action::class)->create();

        $user->updatePermissions([$action2->id => true]);

        $this->assertNotNull($user->actions()->find($action2->id));

        $user->updatePermissions([$action->id => false], true);

        $this->assertNull($user->actions()->find($action2->id));
    }
}