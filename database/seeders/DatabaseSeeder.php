<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Faker\Core\Blood;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ThumbnailSeeder::class,
            NewsSeeder::class,
            ReviewSeeder::class,
            CartSeeder::class,
            WishListSeeder::class,
            BlogSeeder::class,
            RoleSeeder::class,
            CommentSeeder::class,
            OrderDetailSeeder::class,
            BillingAddressSeeder::class
    ]);
    }
}
