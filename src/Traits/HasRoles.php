<?php


namespace Kodilab\LaravelBatuta\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Kodilab\LaravelBatuta\Contracts\Permissionable;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Role;

trait HasRoles
{
    protected static function bootHasRoles()
    {
        self::retrieved(function (Model $model) {
            if ($model->batuta_roles()->get()->isEmpty()) {
                DB::table(config('batuta.tables.role_user', 'role_user'))->insert([
                    'role_id' => Role::getDefault()->id,
                    'user_id' => $model->id
                ]);
            }
        });

        self::saved(function (Model $model) {
            if ($model->batuta_roles()->get()->isEmpty()) {
                DB::table(config('batuta.tables.role_user', 'role_user'))->insert([
                    'role_id' => Role::getDefault()->id,
                    'user_id' => $model->id
                ]);
            }
        });
    }

    /**
     * Role relationship
     *
     * @return BelongsToMany
     */
    public function batuta_roles()
    {
        $table = method_exists($this, 'getRoleRelationshipTable') ?
            $this->getRoleRelationshipTable() : null;

        return $this->belongsToMany(Role::class, $table);
    }

    /**
     * Add a new belonging role
     *
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        $this->batuta_roles()->sync([$role->id], false);
        $this->refresh();
    }

    /**
     * Remove a role association.
     *
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        $this->batuta_roles()->detach($role->id);

        if ($this->batuta_roles()->get()->isEmpty()) {
            $this->addRole(Role::getDefault());
            return;
        }

        $this->refresh();
    }

    /**
     * @param array $roleIds
     * @param bool $detaching
     */
    public function bulkRoles(array $roleIds, bool $detaching = false)
    {
        $this->batuta_roles()->sync($roleIds, $detaching);
        $this->refresh();
    }

    /**
     * Returns whether it belongs to a role
     *
     * @param Role $role
     * @return bool
     */
    public function belongsToRole(Role $role)
    {
        return !is_null($this->batuta_roles()->find($role->id));
    }

    /**
     * Returns true if at least one of roles is granted for a given action. False if any role is granted.
     *
     * @param Action $action
     * @return bool
     */
    private function getInheritedPermission(Action $action)
    {
        if (!in_array(Permissionable::class, class_implements(self::class))) {
            return false;
        }

        /** @var Permissionable $role */
        foreach ($this->batuta_roles as $role) {
            if ($role->hasPermission($action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns whether it belongs to god role
     *
     * @return bool
     */
    public function isGod()
    {
        return !is_null($this->batuta_roles()->find(Role::getGod()->id));
    }

}