<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user_id = User::pluck('id');

        foreach ($user_id as $value) {
            Cart::create([
                'user_id'=>$value,
            ]);
        }
    }
}
