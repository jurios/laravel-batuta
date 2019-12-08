<?php


namespace Kodilab\LaravelBatuta\Traits;


use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kodilab\LaravelBatuta\Models\Role;

/**
 * Trait RegistersGodUser
 * @package Kodilab\LaravelBatuta\Traits
 *
 * @method validator(array $data)
 * @method create(array $data)
 * @method guard()
 * @method registered(Request $request, Authenticatable $user)
 * @method redirectPath()
 */
trait RegistersGodUser
{
    /**
     * Handle a registration request for the application.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register_god(Request $request)
    {
        $this->shouldCreateGodUser();

        $this->validator($request->all())->validate();

        /** @var Authenticatable $user */
        $user = $this->create($request->all());
        $user->addRole(Role::getGod());

        event(new Registered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Returns 404 if a god user is already created
     */
    protected function shouldCreateGodUser()
    {
        if (Role::getGod()->users->isNotEmpty()) {
            abort(404);
        }
    }
}