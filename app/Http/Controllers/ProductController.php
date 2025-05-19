<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('search'); // Lấy từ khóa tìm kiếm từ query string

        // Query cơ bản
        $query = Product::query();

        // Nếu có category_id thì lọc theo category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Nếu có từ khóa tìm kiếm thì lọc theo tên sản phẩm
        if ($search) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }

        // Phân trang sản phẩm
        $products = $query->paginate(8)->withQueryString(); // Giữ lại search/category_id khi phân trang

        // Lấy các dữ liệu khác như trước
        $categories = Category::all();
        $carouselProducts = Product::where('is_featured', true)->take(3)->get();
        $dealEndTime = now()->addDays(7);
        $dealOfTheWeekProduct = Product::find(1);
        $bestSellers = Product::orderByDesc('sales_count')->take(6)->get();
        $latestBlogs = Blog::orderByDesc('published_at')->take(3)->get();
        $brands = Brand::withCount('products')->get();

        return view('clients.pages.home', compact(
            'products',
            'carouselProducts',
            'categories',
            'dealEndTime',
            'categoryId',
            'search',
            'dealOfTheWeekProduct',
            'bestSellers',
            'latestBlogs',
            'brands'
        ));
    }
    public function show($id)
    {
        // Lấy sản phẩm theo ID và kèm theo danh sách ảnh phụ
        $product = Product::with('details', 'images')->findOrFail($id);



        return view('clients.pages.product_detail', compact('product'));
    }
}
