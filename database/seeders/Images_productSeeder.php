<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Image;

class Images_productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách các định dạng ảnh
        $imageExtensions = ['jpg', 'png', 'jpeg', 'webp'];

        // Lấy tất cả sản phẩm
        $products = Product::all();

        // Duyệt qua từng sản phẩm và thêm ảnh mẫu
        foreach ($products as $product) {
            // Lặp qua 3 ảnh cho mỗi sản phẩm
            for ($i = 1; $i <= 3; $i++) {
                // Chọn ngẫu nhiên định dạng ảnh từ danh sách
                $extension = $imageExtensions[array_rand($imageExtensions)];

                // Tạo ảnh với tên và định dạng ngẫu nhiên
                Image::create([
                    'product_id' => $product->product_id, // Khóa ngoại tới bảng products
                    'image_url' => 'image' . $i . '_' . $product->product_id . '.' . $extension, // Đặt tên ảnh với đuôi ngẫu nhiên
                ]);
            }
        }
    }
}
