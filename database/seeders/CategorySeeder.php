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
        for ($i = 1; $i <= 50; $i++) {
            $randomNumber = rand(0, $i) / 2;
            $parent =  $randomNumber > 1 ? floor($randomNumber) : null;
            Category::create([
                'title' => ['en' => "Main category $i", 'fi' => "Pääkategoria $i"],
                'parent_id' => $parent,
            ]);
        }
    }
}
