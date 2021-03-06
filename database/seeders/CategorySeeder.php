<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::find(1);
        for ($i = 1; $i <= 100; $i++) {
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
