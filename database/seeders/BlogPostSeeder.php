<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run()
    {
        DB::table('blogs')->insert([
            [
                'title' => 'Bài Blog Mẫu 1',
                'slug' => 'bai-blog-mau-1',
                'image_url' => 'https://example.com/image1.jpg',
                'author' => 'John Doe',
                'published_at' => now(),
                'content' => 'Đây là nội dung của bài blog mẫu 1.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bài Blog Mẫu 2',
                'slug' => 'bai-blog-mau-2',
                'image_url' => 'https://example.com/image2.jpg',
                'author' => 'Jane Doe',
                'published_at' => now(),
                'content' => 'Đây là nội dung của bài blog mẫu 2.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
