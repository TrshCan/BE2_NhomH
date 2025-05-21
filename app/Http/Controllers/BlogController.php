<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài viết, phân trang 9 bài mỗi trang, sắp xếp theo ngày xuất bản giảm dần
        $posts = Blog::orderBy('published_at', 'desc')->paginate(9);

        // Truyền dữ liệu vào view 'blog'
        return view('clients.pages.blog', compact('posts'));
    }

    public function show($id)
    {
        // Lấy bài viết theo ID
        $post = Blog::findOrFail($id);

        // Truyền dữ liệu vào view chi tiết bài viết
        return view('post', compact('post'));
    }
}
