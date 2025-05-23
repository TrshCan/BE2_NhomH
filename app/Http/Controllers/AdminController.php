<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
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
        $products = Product::with(['category', 'brand'])->paginate(8);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.quanlysanpham', compact('products', 'categories', 'brands'));
    }
    public function adminPanel(){
        return view('layouts.admin');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/')->with('success', 'Đăng xuất thành công.');
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate($this->productRules());

            if ($request->is_featured && $request->price < 10000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm nổi bật phải có giá lớn hơn 10.000đ'
                ], 422);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images'), $imageName);
            $validated['image_url'] = $imageName;

            Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm thành công'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate($this->productRules(true));

            if ($request->is_featured && $request->price < 10000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm nổi bật phải có giá lớn hơn 10.000đ'
                ], 422);
            }

            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($product->image_url && file_exists(public_path('assets/images/' . $product->image_url))) {
                    unlink(public_path('assets/images/' . $product->image_url));
                }
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('assets/images'), $imageName);
                $validated['image_url'] = $imageName;
            } else {
                $validated['image_url'] = $product->image_url;
            }

            $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được cập nhật thành công'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được xóa thành công']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống'], 500);
        }
    }

    private function productRules($isUpdate = false)
    {
        $rules = [
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_featured' => 'required|boolean',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,category_id',
            'brand_id' => 'required|integer|exists:brands,id',
            'sales_count' => 'required|integer|min:0',
        ];

        if ($isUpdate) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['image_url'] = 'sometimes|string';
        } else {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:5048';
        }

        return $rules;
    }
}
