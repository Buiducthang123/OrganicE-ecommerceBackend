<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $user_id = User::pluck('id');
        $blog_id = Blog::pluck('id');
        for ($i = 0; $i < 10; $i++) {
            Comment::create([
                'user_id' => $faker->randomElement($user_id),
                'blog_id' => 1,
                'content' => $faker->paragraph(),
            ]);
        }
    }
}
