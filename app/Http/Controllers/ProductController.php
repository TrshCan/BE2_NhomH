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
        $page = $request->query('page', 1); // Giá trị mặc định là trang 1
        $perPage = 8; // Số sản phẩm mỗi trang

        // Xác thực tham số category_id
        if ($categoryId !== null) {
            if (!is_numeric($categoryId) || $categoryId < 1) {
                return redirect()->route('products.home', ['search' => $search])
                    ->with('error', 'Danh mục không hợp lệ. Vui lòng chọn danh mục khác.');
            }

            // Kiểm tra xem category_id có tồn tại
            $categoryExists = Category::where('category_id', $categoryId)->exists();
            if (!$categoryExists) {
                return redirect()->route('products.home', ['search' => $search])
                    ->with('error', 'Danh mục không tồn tại. Vui lòng chọn danh mục khác.');
            }
        }

        // Xác thực tham số page
        if (!is_numeric($page) || $page < 1) {
            return redirect()->route('products.home', ['category_id' => $categoryId, 'search' => $search])
                ->with('error', 'Tham số trang không hợp lệ. Vui lòng thử lại.');
        }

        // Tạo truy vấn cơ bản
        $query = Product::query()->byCategory($categoryId)->search($search);

        // Kiểm tra số trang tối đa
        $totalProducts = $query->count();
        $maxPages = ceil($totalProducts / $perPage);

        if ($page > $maxPages && $totalProducts > 0) {
            return redirect()->route('products.home', ['page' => $maxPages, 'category_id' => $categoryId, 'search' => $search])
                ->with('error', 'Trang bạn yêu cầu không tồn tại. Đã chuyển đến trang cuối cùng.');
        }

        // Thực hiện phân trang
        $products = $query->paginate($perPage)->withQueryString();

        // Lấy dữ liệu khác
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
        // Xác thực tham số id
        if (!is_numeric($id) || $id < 1) {
            return redirect()->route('products.home')
                ->with('error', 'ID sản phẩm không hợp lệ. Vui lòng thử lại.');
        }

        try {
            // Kiểm tra xem sản phẩm có tồn tại trước
            if (!Product::where('product_id', $id)->exists()) {
                return redirect()->route('products.home')
                    ->with('error', 'Sản phẩm không tồn tại. Vui lòng chọn sản phẩm khác.');
            }

            // Lấy sản phẩm theo ID kèm chi tiết và ảnh phụ
            $product = Product::getProductWithDetails($id);

            // Đếm số lượng đánh giá của sản phẩm
            $count = Review::countByProductId($id);

            // Lấy các đánh giá với phân trang
            $reviews = Review::getReviewsByProductId($id);

            return view('clients.pages.product_detail', compact('product', 'reviews', 'count'));
        } catch (Exception $e) {
            return redirect()->route('products.home')
                ->with('error', 'Không tìm thấy sản phẩm. Vui lòng thử lại.');
        }
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
