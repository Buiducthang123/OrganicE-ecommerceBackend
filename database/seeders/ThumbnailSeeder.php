<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Thumbnail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $products_id = Product::pluck('id');
        // for ($i=0; $i < 15; $i++) { 
        //    Thumbnail::create([
        //     "product_id"=>$faker->randomElement($products_id),
        //     "imageUrl"=>$faker->imageUrl()
        //    ]);
        // }
        foreach ($products_id as $value) {
            for ($i=0; $i < 5; $i++) { 
                # code...
                Thumbnail::create([
                    "product_id" => ($value),
                    "imageUrl" => $faker->imageUrl()
                ]);
            }
          
        }
    }
}
