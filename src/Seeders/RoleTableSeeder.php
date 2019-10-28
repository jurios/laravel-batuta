<?php

namespace Kodilab\LaravelBatuta\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Kodilab\LaravelBatuta\Exceptions\DefaultRoleAlreadyExists;
use Kodilab\LaravelBatuta\Exceptions\GodRoleAlreadyExists;
use Kodilab\LaravelBatuta\Models\Role;

class RoleTableSeeder extends Seeder
{

    /**
     * Default role name
     *
     * @var string
     */
    protected $default_role_name = 'default';

    /**
     * God role name
     *
     * @var string
     */
    protected $god_role_name = 'god';

    /**
     * @var string
     */
    private $table;

    public function __construct()
    {
        $this->table = config('batuta.tables.roles', 'roles');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createDefaultRole();

        if (config('batuta.allow_god', true)) {
            $this->createGodRole();
        }
    }

    /**
     * Creates a default role
     */
    protected function createDefaultRole()
    {
        if (Role::where('default', true)->get()->isNotEmpty()) {
            throw new DefaultRoleAlreadyExists();
        }

        \Kodilab\LaravelBatuta\Models\Role::create([
            'name' => $this->default_role_name,
            'god' => false,
            'default' => true
        ]);
    }

    /**
     * Creates a god role
     */
    protected function createGodRole()
    {
        if (Role::where('god', true)->get()->isNotEmpty()) {
            throw new GodRoleAlreadyExists();
        }

        \Kodilab\LaravelBatuta\Models\Role::create([
            'name' => $this->god_role_name,
            'god' => true,
            'default' => false
        ]);

        return;
    }
}