<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = ["Thực phẩm tươi sống","Thực phẩm khô","đồ uống"];
        foreach ($categories as  $value) {
            # code...
            category::create([
                "name"=>$value
            ]);
        }

    }
}
