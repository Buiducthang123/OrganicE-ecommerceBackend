<?php

namespace Database\Seeders;

use App\Models\BillingAddress;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class BillingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user_ids = User::pluck("id");
        $faker = Faker::create();
        foreach ($user_ids as $value) {
            BillingAddress::create([
                "user_id"=>$value,
                "name"=>$faker->name,
                "address"=>$faker->address,
                "email"=>$faker->email ,
                "phone"=>$faker->phoneNumber,
            ]);
        }
    }
}
