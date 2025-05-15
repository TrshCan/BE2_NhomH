<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('seeders/products_realistic_full.csv'), 'r');
        $csv->setHeaderOffset(0); // row 1 as headers
    
        foreach ($csv as $record) {
            DB::table('products')->insert([
                'product_name' => $record['product_name'],
                'description' => $record['description'],
                'price' => $record['price'],
                'stock_quantity' => $record['stock_quantity'],
                'brand_id' => DB::table('brands')->where('name', $record['brand_name'])->value('id'),
                'image_url' => $record['image_url'],
                'category_id' => rand(1, 5),
                'sales_count' => rand(0, 1000),
            ]);
        }
    }
}
