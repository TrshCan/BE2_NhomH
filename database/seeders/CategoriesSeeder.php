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
            ['category_name' => 'Điện thoại', 'description' => 'Các dòng điện thoại mới nhất'],
            ['category_name' => 'Laptop', 'description' => 'Laptop phụ vụ công việc và học tập'],
            ['category_name' => 'Phụ kiện', 'description' => 'Phụ kiện công nghệ'],
        ]);
    }
}
