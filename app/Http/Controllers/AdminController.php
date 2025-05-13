<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Promotion;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.quanlysanpham', compact('products'));
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
}