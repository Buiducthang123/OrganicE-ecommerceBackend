<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Testing\Fakes\Fake;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users_id = User::pluck('id');
        for ($i=0; $i < 10; $i++) { 
            Review::create([
                "user_id"=>$faker->randomElement($users_id),
                "content"=>$faker->paragraph(),
                "rate"=>$faker->randomElement([1,2,3,4,5]),
            ]);
        }
    }
}
