<?php


namespace Kodilab\LaravelBatuta\Tests\fixtures\Models;


use Illuminate\Database\Eloquent\Model;
use Kodilab\LaravelBatuta\Traits\HasPermissions;

class ModelNotImplementsPermissionable extends Model
{
    use HasPermissions;
}