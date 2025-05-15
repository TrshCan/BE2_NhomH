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
            ['name' => 'Nike', 'logo_url' => 'brands/nike.png'],
            ['name' => 'Adidas', 'logo_url' => 'brands/adidas.png'],
            ['name' => 'Puma', 'logo_url' => 'brands/puma.png'],
            ['name' => 'Gucci', 'logo_url' => 'brands/gucci.png'],
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
