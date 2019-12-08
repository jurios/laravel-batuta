<?php


namespace Kodilab\LaravelBatuta\Tests\fixtures\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodilab\LaravelBatuta\Contracts\Permissionable;
use Kodilab\LaravelBatuta\Traits\HasRoles;
use Kodilab\LaravelBatuta\Traits\UserPermissions;

class User extends Authenticatable implements Permissionable
{
    use UserPermissions, HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $inheritPermissions = true;

    private function shouldInheritPermissions()
    {
        return $this->inheritPermissions;
    }
}