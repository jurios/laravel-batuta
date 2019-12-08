<?php


namespace Kodilab\LaravelBatuta\Tests\Feature;


use Kodilab\LaravelBatuta\Models\Role;
use Kodilab\LaravelBatuta\Support\Facades\Facade;
use Kodilab\LaravelBatuta\Tests\fixtures\Models\User;
use Kodilab\LaravelBatuta\Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Facade::godRegistration('Kodilab\LaravelBatuta\Tests\fixtures\Controllers\RegisterController');
    }

    public function test_a_god_registration_should_return_404_if_a_god_user_already_exists()
    {
        $user = factory(User::class)->create();
        $user->addRole(Role::getGod());

        $this->post(route('register.god', factory(User::class)->make()->toArray()))->assertStatus(404);
    }

    public function test_a_god_user_is_created()
    {
       $user = factory(User::class)->make();

       $this->post(route('register.god', $user->toArray()))->assertStatus(302);

       $user = User::where('email', $user->email)->get()->first();

       $this->assertNotNull($user);
       $this->assertTrue($user->isGod());
    }
}