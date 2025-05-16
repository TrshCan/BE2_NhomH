<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function productIndex()
    {
        $products = Product::all();
        return view('admin.quanlysanpham', compact('products'));
    }

    public function index()
    {
        // Default date range (last 30 days)
        $toDate = Carbon::now();
        $fromDate = Carbon::now()->subDays(30);

        return $this->getStatistics($fromDate, $toDate, 0);
    }

    public function filter(Request $request)
    {
        $fromDateStr = $request->input('from_date');
        $toDateStr = $request->input('to_date');
        $categoryId = $request->input('category_id', 0);

        // Parse date strings (DD/MM/YYYY format)
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDateStr)->startOfDay();
        $toDate = Carbon::createFromFormat('d/m/Y', $toDateStr)->endOfDay();

        return $this->getStatistics($fromDate, $toDate, $categoryId);
    }

    public function getStatistics($fromDate, $toDate, $categoryId)
    {
        $fromDateFormatted = $fromDate->format('d/m/Y');
        $toDateFormatted = $toDate->format('d/m/Y');
        $selectedCategory = (int)$categoryId;

        $categories = Category::orderBy('category_name')->get();

        // Category filter logic
        $ordersQuery = Order::getOrdersWithinDateRange($fromDate, $toDate);
        $categoryFilter = ($categoryId > 0);

        // Get total revenue and order count
        if ($categoryFilter) {
            $revenueData = OrderDetail::getRevenueData($fromDate, $toDate, $categoryId);
            $totalRevenue = $revenueData->total_revenue ?? 0;
            $orderCount = $revenueData->order_count ?? 0;
        } else {
            // No category filter, simple calculation
            $totalRevenue = $ordersQuery->sum('total_amount');
            $orderCount = $ordersQuery->count();
        }
        
        $monthlyRevenue = Order::getMonthlyRevenue($fromDate, $toDate, $categoryId);
        $categoryRevenue = OrderDetail::getCategoryRevenue($fromDate, $toDate, $categoryId);
        $topProducts = OrderDetail::getTopProducts($fromDate, $toDate, $categoryId);
        $topProduct = $topProducts->first();
        $orderStatusData = Order::getOrderStatusDistribution($fromDate, $toDate, $categoryId);

        return view('admin.statistical', compact(
            'fromDate',
            'toDate',
            'fromDateFormatted',
            'toDateFormatted',
            'totalRevenue',
            'orderCount',
            'monthlyRevenue',
            'categoryRevenue',
            'categories',
            'selectedCategory',
            'topProducts',
            'topProduct',
            'orderStatusData'
        ));
    }
}
    // public function show($id)
    // {
    //     try {
    //         $product = Product::findOrFail($id);
    //         return response()->json($product);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm: ' . $e->getMessage()], 404);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'product_name' => 'required|string|max:255',
    //             'description' => 'required|string',
    //             'price' => 'required|numeric',
    //             'is_featured' => 'required|boolean',
    //             'stock_quantity' => 'required|integer',
    //             'category_id' => 'required|integer|exists:categories,id',
    //             'brand_id' => 'required|integer|exists:brands,id',
    //             'image_url' => 'required|string',
    //             'sales_count' => 'required|integer',
    //         ]);

    //         $product = Product::create($validated);
    //         return response()->json(['success' => true, 'message' => 'Sản phẩm đã được thêm thành công']);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json(['success' => false, 'message' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'product_name' => 'required|string|max:255',
    //             'description' => 'required|string',
    //             'price' => 'required|numeric',
    //             'is_featured' => 'required|boolean',
    //             'stock_quantity' => 'required|integer',
    //             'category_id' => 'required|integer|exists:categories,id',
    //             'brand_id' => 'required|integer|exists:brands,id',
    //             'image_url' => 'required|string',
    //             'sales_count' => 'required|integer',
    //         ]);

    //         $product = Product::findOrFail($id);
    //         $product->update($validated);
    //         return response()->json(['success' => true, 'message' => 'Sản phẩm đã được cập nhật thành công']);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json(['success' => false, 'message' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $product = Product::findOrFail($id);
    //         $product->delete();
    //         return response()->json(['success' => true, 'message' => 'Sản phẩm đã được xóa thành công']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm: ' . $e->getMessage()], 500);
    //     }
    // }
