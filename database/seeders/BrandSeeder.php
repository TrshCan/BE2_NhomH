<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $brands = [
            ['name' => 'Sony', 'logo_url' => 'Sony.png'],
            ['name' => 'Samsung', 'logo_url' => 'Samsung.png'],
            ['name' => 'Apple', 'logo_url' => 'Apple.png'],
            ['name' => 'Asus', 'logo_url' => 'Asus.png'],
            ['name' => 'Canon', 'logo_url' => 'Canon.png'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo_url' => $brand['logo_url'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
