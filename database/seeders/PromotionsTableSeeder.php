<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('promotions')->insert([
            [
                'title' => 'Tech Accessories 2025',
                'description' => 'Up to 25% Off on Keyboards & Headsets',
                'image_url' => 'oneplus-keyboard.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Arrivals 2025',
                'description' => 'Explore Top Headsets',
                'image_url' => 'labubu.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Gaming Laptops',
                'description' => 'Save Big This Season',
                'image_url' => 'AK_600.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
