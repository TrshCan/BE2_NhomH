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
        $products = DB::table('products')->select('product_id', 'price')->get();

        // Tạo dữ liệu cho bảng order_details
        for ($i = 0; $i < 500; $i++) { // Tạo 5000 dòng dữ liệu ngẫu nhiên
            $selectedProduct = $faker->randomElement($products); // Chọn ngẫu nhiên một sản phẩm

            DB::table('order_details')->insert([
                'order_id' => $faker->randomElement($orderIds), // Chọn ngẫu nhiên một order_id
                'product_id' => $selectedProduct->product_id, // Lấy product_id từ danh sách
                'quantity' => $faker->numberBetween(1, 10), // Số lượng sản phẩm
                'price' => $selectedProduct->price, // Lấy giá của sản phẩm từ bảng products
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

