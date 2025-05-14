<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [   'product_id' =>  1,
                'product_name'   => 'Tai nghe Bluetooth Sony WH-1000XM4',
                'description'    => 'Tai nghe chống ồn cao cấp với thời lượng pin 30 giờ.',
                'price'          => 6990000,
                'stock_quantity' => 50,
                'category_id'    => 1,
                'brand_id'       => 1,
                'image_url'      => 'images/sony_wh1000xm4.jpg',
                'sales_count'    => 120,
            ],
            [   'product_id' =>  2,
                'product_name'   => 'Chuột Logitech G502 Hero',
                'description'    => 'Chuột gaming với cảm biến HERO 25K chính xác.',
                'price'          => 1299000,
                'stock_quantity' => 100,
                'category_id'    => 2,
                'brand_id'       => 2,
                'image_url'      => 'images/logitech_g502.jpg',
                'sales_count'    => 300,
            ],
            [   'product_id' =>  3,
                'product_name'   => 'Bàn phím cơ Keychron K6',
                'description'    => 'Bàn phím không dây 65% với switch Gateron.',
                'price'          => 1890000,
                'stock_quantity' => 75,
                'category_id'    => 3,
                'brand_id'       => 3,
                'image_url'      => 'images/keychron_k6.jpg',
                'sales_count'    => 210,
            ]
        ]);
    }
}
