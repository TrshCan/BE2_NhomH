<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDetailsSeeder extends Seeder
{
    public function run()
    {
        DB::table('product_details')->insert([
            'product_id' => 1, // Giả sử product_id = 1 tồn tại
            'model' => 'Black, Red, White',
            'connectivity' => 'USB, 3.5mm Jack',
            'compatibility' => 'PC, PS4, Xbox, Mobile',
            'weight' => '346g',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('product_details')->insert([
            'product_id' => 2, // Giả sử product_id = 1 tồn tại
            'model' => 'Blue, Red, Green',
            'connectivity' => 'USB, 3.5mm Jack',
            'compatibility' => 'PC, PS4, Xbox, Mobile',
            'weight' => '346g',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
