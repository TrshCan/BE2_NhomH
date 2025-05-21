<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy category_id từ query string nếu có
        $categoryId = $request->query('category_id');

        // Nếu có category_id, lọc sản phẩm theo category_id, nếu không lấy tất cả sản phẩm
        $products = $categoryId
            ? Product::where('category_id', $categoryId)->paginate(20)
            : Product::paginate(20); // Nếu không có category_id thì lấy tất cả sản phẩm

        // Lấy tất cả các danh mục
        $categories = Category::all();

        // Lấy 3 sản phẩm nổi bật
        $carouselProducts = Product::where('is_featured', true)->take(3)->get();

        // Giả sử thời gian kết thúc deal là 7 ngày từ bây giờ
        $dealEndTime = now()->addDays(7);
        $dealOfTheWeekProduct = Product::find(1);
        $bestSellers = Product::orderByDesc('sales_count')->take(6)->get();
        $latestBlogs = Blog::orderByDesc('published_at')->take(3)->get();
        $brands = Brand::withCount('products')->get();


        // Truyền thời gian kết thúc deal dưới dạng timestamp
        return view('clients.pages.home', compact('products', 'carouselProducts', 'categories', 'dealEndTime', 'categoryId', 'dealOfTheWeekProduct', 'bestSellers', 'latestBlogs', 'brands'));
    }
    public function show($id)
    {
        // Lấy sản phẩm theo ID và kèm theo danh sách ảnh phụ
        $product = Product::with('details', 'images')->findOrFail($id);



        return view('clients.pages.product_detail', compact('product'));
    }

    public function get($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'product_id' => $product->product_id,
                'product_name' => $product->product_name,
                'price' => $product->price,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Không thể lấy thông tin sản phẩm'], 500);
        }
    }
   
}
