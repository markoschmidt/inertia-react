<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $max = Category::count();
        for ($i = 1; $i <= 1000; $i++) {
            $product = Product::create([
                'name' => ['en' => "Product $i", 'fi' => "Tuote $i"],
                'description' => ['en' => "Description for product $i", 'fi' => "Kuvaus tuottelle $i"],
            ]);

            $random = [];
            for ($j = 0; $j < random_int(1, 3); $j++) {
                $random[] = random_int(1, $max);
            }
            $categories = Category::find($random)->pluck('id');
            $product->categories()->attach($categories);

        }
    }
}
