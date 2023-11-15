<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        // Tạo dữ liệu mẫu với Faker
        for ($i = 0; $i < 10; $i++) {
          OrderDetail::create([
                'user_id' => $faker->numberBetween(1, 10),
                'products_order' => json_encode([
                    [
                        'id' => $faker->numberBetween(1, 5),
                        "name"=> $faker->name,
                        'quantity' => $faker->numberBetween(1, 5),
                        'price' => $faker->randomFloat(2, 10, 100),
                    ],
                    [
                        'id' => $faker->numberBetween(1, 5),
                        'name'=> $faker->name,
                        'quantity' => $faker->numberBetween(1, 5),
                        'price' => $faker->randomFloat(2, 10, 100),
                    ],
                ]),
                
                'total_price' => $faker->randomFloat(2, 50, 200),
                'address_shipping' => $faker->address,
                'payment_method' => $faker->creditCardType,
                'note' => $faker->text,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
