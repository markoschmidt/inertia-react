<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 500; $i++) {
            $randomNumber = rand(0, $i) / 2;
            $parent =  $randomNumber > 1 ? floor($randomNumber) : null;
            Category::create([
                'title' => ['en' => "Main category $i", 'fi' => "Pääkategoria $i"],
                'parent_id' => $parent,
            ]);

            $permission = Permission::create([
                'name' => ['en' => 'categories.write.'.$i, 'fi' => 'categories.write.'.$i]
            ]);
            $addPermission = random_int(0, 1);

            if ($addPermission) {
                $admin->givePermissionTo($permission->id);
            }

            $permission = Permission::create([
                'name' => ['en' => 'categories.read.'.$i, 'fi' => 'categories.read.'.$i]
            ]);
            $addPermission = random_int(0, 1);

            if ($addPermission) {
                $admin->givePermissionTo($permission->id);
            }
        }
    }
}
