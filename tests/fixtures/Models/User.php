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
}