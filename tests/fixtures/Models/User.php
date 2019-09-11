<?php


namespace Kodilab\LaravelBatuta\Tests\fixtures\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodilab\LaravelBatuta\Contracts\Permissionable;
use Kodilab\LaravelBatuta\Traits\HasPermissions;
use Kodilab\LaravelBatuta\Traits\HasRoles;

class User extends Authenticatable implements Permissionable
{
    use HasPermissions, HasRoles;
    use Notifiable;

    public $inheritPermissions = true;

    public function getPermissionsTable()
    {
        return config('batuta.tables.user_permissions', 'user_permissions');
    }

    private function shouldInheritPermissions()
    {
        return $this->inheritPermissions;
    }
}