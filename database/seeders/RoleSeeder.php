<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allPermissions = Permission::all();
        $edit = Permission::where('name', 'edit-user')->get();
        $show = Permission::where('name', 'show-user')->get();
        $role = Role::create([
            'name' => ['en' => 'Admin', 'fi' => 'Ylläpitäjä'],
            'guard_name' => 'admin',
        ]);
        $role->permissions()->attach($allPermissions);

        $role = Role::create([
            'name' => ['en' => 'Editor', 'fi' => 'Editori'],
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($edit);

        $role = Role::create([
            'name' => ['en' => 'User', 'fi' => 'Käyttäjä'],
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($show);
    }
}
