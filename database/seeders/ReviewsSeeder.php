<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $images = [
            'assets/images/camera_1.png',
            'assets/images/camera_2.png',
            'assets/images/camera_3.png',
            'assets/images/camera.png',
            'assets/images/Canon.png',
            'assets/images/chair_1.png',
            'assets/images/chair_2.png',
            'assets/images/chair_3.png',
            'assets/images/chair.png',
            'assets/images/close.png',
            'assets/images/d1-4946.jpeg',
            'assets/images/dell.jpg',
            'assets/images/DJI2.jpg',
        ];

        foreach (range(1, 50) as $index) {
            DB::table('reviews')->insert([
                'user_id' => rand(1, 20), // Random user ID (giả sử có 20 user)
                'product_id' => rand(1, 15), // Random product ID (giả sử có 15 sản phẩm)
                'rating' => rand(1, 5), // Random rating từ 1 đến 5
                'comment' => 'This is a sample review comment #' . $index,
                'image' => $images[array_rand($images)], // Random image
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}