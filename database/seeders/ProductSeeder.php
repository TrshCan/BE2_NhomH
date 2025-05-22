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
        try {
            $csv = Reader::createFromPath(database_path('seeders/products_seeder.csv'), 'r');
            $csv->setHeaderOffset(0); // Row 1 as headers

            foreach ($csv as $record) {
                DB::table('products')->insert([
                    'product_name' => $record['product_name'],
                    'description' => $record['description'],
                    'price' => (float) $record['price'], // Ensure DECIMAL(10,2)
                    'is_featured' => (int) $record['is_featured'], // Ensure TINYINT(1)
                    'stock_quantity' => (int) $record['stock_quantity'], // Ensure INT(11)
                    'category_id' => (int) $record['category_id'], // Use CSV value (1, 2, or 3)
                    'brand_id' => (int) $record['brand_id'], // Use CSV value (1–5)
                    'image_url' => $record['image_url'],
                    'sales_count' => (int) $record['sales_count'], // Use CSV value
                    'created_at' => $record['created_at'], // Use CSV timestamp
                    'updated_at' => $record['updated_at'], // Use CSV timestamp
                ]);
            }
        } catch (\Exception $e) {
            // Log the error and stop the seeder
            \Log::error('Product seeder failed: ' . $e->getMessage());
            throw new \Exception('Failed to seed products: ' . $e->getMessage());
        }
    }
}
