<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách order_id và product_id từ các bảng liên quan
        $orderIds = DB::table('orders')->pluck('order_id')->toArray();
        $productIds = DB::table('products')->pluck('product_id')->toArray();

        // Tạo dữ liệu cho bảng order_details
        for ($i = 0; $i < 50; $i++) { // Tạo 50 dòng dữ liệu ngẫu nhiên
            DB::table('order_details')->insert([
                'order_id' => $faker->randomElement($orderIds), // Chọn ngẫu nhiên một order_id
                'product_id' => $faker->randomElement($productIds), // Chọn ngẫu nhiên một product_id
                'quantity' => $faker->numberBetween(1, 10), // Số lượng sản phẩm
                'price' => $faker->randomFloat(2, 10, 500), // Giá của sản phẩm
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
}}
