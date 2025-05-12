<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        Promotion::create([
            'title' => 'Tech Accessories 2025',
            'description' => 'Up to 25% Off on Keyboards & Headsets',
            'image_url' => 'oneplus-keyboard.jpg',
        ]);
        Promotion::create([
            'title' => 'New Arrivals 2025',
            'description' => 'Explore Top Headsets',
            'image_url' => 'labubu.png',
        ]);
        Promotion::create([
            'title' => 'Gaming Laptops',
            'description' => 'Save Big This Season',
            'image_url' => 'AK_600.jpg',
        ]);
    }
}
