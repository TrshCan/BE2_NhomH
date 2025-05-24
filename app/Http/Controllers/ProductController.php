<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Review;
use Exception;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('search');

        $products = Product::query()
            ->byCategory($categoryId)
            ->search($search)
            ->paginate(8)
            ->withQueryString();

        $categories = Category::all();
        $carouselProducts = Product::featured()->get();
        $dealEndTime = now()->addDays(4);
        $dealOfTheWeekProduct = Product::dealOfTheWeek()->with('deal')->first();

        $bestSellers = Product::bestSellers()->get();
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
        // Lấy sản phẩm theo ID kèm chi tiết và ảnh phụ
    $product = Product::getProductWithDetails($id);

    // Đếm số lượng đánh giá của sản phẩm
    $count = Review::countByProductId($id);

    // Lấy các đánh giá với phân trang
    $reviews = Review::getReviewsByProductId($id);
        return view('clients.pages.product_detail',  compact('product', 'reviews', 'count'));
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