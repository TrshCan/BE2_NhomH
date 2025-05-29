<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'category_name' => 'Điện thoại',
                'description' => 'Các dòng điện thoại mới nhất',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Laptop',
                'description' => 'Laptop phục vụ công việc và học tập',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Phụ kiện',
                'description' => 'Phụ kiện công nghệ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
