<?php


namespace Kodilab\LaravelBatuta\Tests\Unit\Traits;


use Illuminate\Support\Facades\DB;
use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Tests\fixtures\Models\User;
use Kodilab\LaravelBatuta\Tests\TestCase;

class HasRolesTest extends TestCase
{
    /** @var User */
    protected $user;

    /** @var Role */
    protected $role;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create();
    }

    public function test_automatically_add_default_role_when_a_item_without_roles_is_retrieved()
    {
        $userData = factory(User::class)->make()->toArray();

        DB::table(config('batuta.tables.user', 'users'))->insert($userData);

        /** @var User $user */
        $user = User::where('email', $userData['email'])->get()->first();

        $this->assertNotNull($user->batuta_roles()->find(Role::getDefault()->id));
    }

    public function test_automatically_add_default_role_when_a_item_without_roles_is_persisted()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->assertNotNull($user->batuta_roles()->find(Role::getDefault()->id));
    }

    public function test_addRole_should_add_the_role()
    {
        $this->assertNull($this->user->batuta_roles()->find($this->role->id));

        $this->user->addRole($this->role);

        $this->assertNotNull($this->user->batuta_roles()->find($this->role->id));
    }

    public function test_removeRole_should_remove_the_role()
    {
        $this->user->addRole($this->role);

        $this->assertNotNull($this->user->batuta_roles()->find($this->role->id));

        $this->user->removeRole($this->role);

        $this->assertNull($this->user->batuta_roles()->find($this->role->id));
    }

    public function test_removeRole_should_add_the_default_role_if_no_roles_associations_remains()
    {
        $this->user->addRole($this->role);

        $this->user->removeRole($this->role);

        $this->assertNotNull($this->user->batuta_roles()->find(Role::getDefault()->id));
    }

    public function test_bulkRoles_should_add_multiple_roles()
    {
        $roles = factory(Role::class, 10)->create();

        $this->user->bulkRoles($roles->pluck('id')->toArray());

        /** @var Role $role */
        foreach ($roles as $role)
        {
            $this->assertNotNull($this->user->batuta_roles()->find($role->id));
        }
    }

    public function test_bulkRoles_should_not_remove_previous_role_associations()
    {
        $this->user->addRole($this->role);

        $roles = factory(Role::class, 10)->create();

        $this->user->bulkRoles($roles->pluck('id')->toArray());

        $this->assertNotNull($this->user->batuta_roles()->find($this->role->id));
    }

    public function test_bulkRoles_should_remove_previous_roles_if_detaching_mode_is_true()
    {
        $this->user->addRole($this->role);

        $roles = factory(Role::class, 10)->create();

        $this->user->bulkRoles($roles->pluck('id')->toArray(), true);

        $this->assertNull($this->user->batuta_roles()->find($this->role->id));
    }

    public function test_belongsToRole_should_returns_whether_it_belongs_to_role()
    {
        $this->assertFalse($this->user->belongsToRole($this->role));

        $this->user->addRole($this->role);

        $this->assertTrue($this->user->belongsToRole($this->role));
    }

    public function test_isGod_should_return_true_if_it_belongs_to_god_role()
    {
        $this->user->addRole($this->role);

        $this->assertFalse($this->user->isGod());

        $this->user->addRole(Role::getGod());

        $this->assertTrue($this->user->isGod());
    }
}