<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\wishList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use GuzzleHttp\Promise\Create;

class WishListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $faker = Faker::create();
        $users_id = User::pluck('id')->toArray();
        $products_id = Product::inRandomOrder()->limit(10)->pluck('id')->toArray();
        // Giới hạn số lượng sản phẩm giả được tạo (ở đây giới hạn là 10 sản phẩm).

        $existingWishLists = WishList::whereIn('user_id', $users_id)
            ->whereIn('product_id', $products_id)
            ->get();

        $existingPairs = $existingWishLists->pluck(['user_id', 'product_id'])->toArray();

        foreach ($users_id as $user_id) {
            foreach ($products_id as $product_id) {
                if (!in_array([$user_id, $product_id], $existingPairs)) {
                    WishList::create([
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                    ]);
                }
            }
        }
    }
}
