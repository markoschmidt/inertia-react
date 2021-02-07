<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => ['en' => 'edit-user', 'fi' => 'edit-user']
        ]);
        Permission::create([
            'name' => ['en' => 'create-user', 'fi' => 'create-user']
        ]);
        Permission::create([
            'name' => ['en' => 'show-user', 'fi' => 'show-user']
        ]);
    }
}
