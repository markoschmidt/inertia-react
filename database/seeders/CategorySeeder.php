<?php

namespace Database\Seeders;

use App\Models\Category;
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
        Category::create([
            'title' => ['en' => 'Main category 1', 'fi' => 'Pääkategoria 1'],
        ]);
        Category::create([
            'title' => ['en' => 'Main category 2', 'fi' => 'Pääkategoria 2'],
        ]);
        Category::create([
            'title' => ['en' => 'Main category 3', 'fi' => 'Pääkategoria 3'],
        ]);
        Category::create([
            'title' => ['en' => 'Subcategory 1-1', 'fi' => 'Alakategoria 1-1'],
            'parent_id' => 1
        ]);
        Category::create([
            'title' => ['en' => 'Subcategory 1-2', 'fi' => 'Alakategoria 1-2'],
            'parent_id' => 1
        ]);
        Category::create([
            'title' => ['en' => 'Subcategory 3-1', 'fi' => 'Alakategoria 3-1'],
            'parent_id' => 3
        ]);
        Category::create([
            'title' => ['en' => 'Subcategory 3-2', 'fi' => 'Alakategoria 3-2'],
            'parent_id' => 3
        ]);
        Category::create([
            'title' => ['en' => 'Subcategory 3-1-1', 'fi' => 'Alakategoria 3-1-1'],
            'parent_id' => 6
        ]);
    }
}
