<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $categories_id = category::pluck("id")->toArray();
        for ($i = 0; $i < 100; $i++) {
            Blog::create([
                'category_id'=>$faker->randomElement($categories_id),
                'title'=>$faker->title(),
                'image'=>$faker->imageUrl(),
                'content'=>$faker->paragraphs(5, true),
            ]);
        }
    }
}
