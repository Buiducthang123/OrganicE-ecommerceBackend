<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Testing\Fakes\Fake;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $categories = ["Thực phẩm tươi sống","Thực phẩm khô","đồ uống","Mixedfruits"];
        foreach ($categories as  $value) {
            # code...
            category::create([
                "name"=>$value,
                "image"=>$faker->imageUrl()
            ]);
        }

    }
}
