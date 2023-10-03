<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $categories_id = category::pluck('id');
        foreach (range(1, 10) as $index) {
            Product::create([
                'category_id' => $faker->randomElement($categories_id),
                'name' => $faker->word,
                'type' => $faker->word,
                'quantity' => $faker->numberBetween(1, 100),
                'imageUrl'=>$faker->imageUrl(),
                'average_rating' => $faker->randomElement([1, 2, 3, 4, 5]),
                'discount' => $faker->numberBetween(1, 100),
                "sales_count"=>$faker->numberBetween(1,1000),
                'weight' => $faker->randomFloat(2, 0.1, 1000.0),
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 10, 1000),
               
            ]);
        }
    }
}
