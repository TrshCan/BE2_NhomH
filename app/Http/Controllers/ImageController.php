<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImageController extends Controller
{
    public function index()
    {
        try {
            $images = Image::paginate(10);
            $products = Product::all();
            return view('admin.quanlyanh', compact('images', 'products'));
        } catch (\Exception $e) {
            Log::error('Error in ImageController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $products = Product::all();
            return view('admin.quanlyanh', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in ImageController@create: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'product_id' => 'required|integer|exists:products,product_id',
            ], [
                'image.required' => 'Vui lòng chọn ảnh cho danh mục',
                'image.image' => 'Hình ảnh phải có định dạng .jpg, .png, jpeg',
                'image.mimes' => 'Hình ảnh phải có định dạng .jpg, .png, jpeg',
                'image.max' => 'Hình ảnh không được vượt quá 2mb',
                'product_id.required' => 'Không được để trống', // Thay vì "tên danh mục", dùng chung cho product_id
                'product_id.integer' => 'ID sản phẩm phải là số nguyên',
                'product_id.exists' => 'ID sản phẩm không tồn tại'
            ]);

            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('assets/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $fileSize = $file->getSize();
            if ($fileSize > 2048 * 1024) {
                throw new \Exception('Hình ảnh không được vượt quá 2mb');
            }

            $file->move($destination, $filename);

            $image = new Image();
            $image->image_url = $filename;
            $image->product_id = $request->product_id;
            $image->save();

            return response()->json(['success' => true, 'message' => 'Ảnh đã được thêm thành công!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in ImageController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $image = Image::findOrFail($id);
            return response()->json(['success' => true, 'data' => $image]);
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found with ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh với ID: ' . $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in ImageController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $image = Image::findOrFail($id);
            $products = Product::all();
            return response()->json(['success' => true, 'data' => $image, 'products' => $products]);
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found with ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh với ID: ' . $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in ImageController@edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $image = Image::findOrFail($id);

            $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'product_id' => 'required|integer|exists:products,product_id',
            ], [
                'image.image' => 'Hình ảnh phải có định dạng .jpg, .png, jpeg',
                'image.mimes' => 'Hình ảnh phải có định dạng .jpg, .png, jpeg',
                'image.max' => 'Hình ảnh không được vượt quá 2mb',
                'product_id.required' => 'Không được để trống',
                'product_id.integer' => 'ID sản phẩm phải là số nguyên',
                'product_id.exists' => 'ID sản phẩm không tồn tại'
            ]);

            if ($request->hasFile('image')) {
                $oldPath = public_path('assets/images/' . $image->image_url);
                if (file_exists($oldPath) && is_writable($oldPath)) {
                    unlink($oldPath);
                } else {
                    Log::warning("Cannot delete file: $oldPath");
                }

                $file = $request->file('image');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $destination = public_path('assets/images');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $fileSize = $file->getSize();
                if ($fileSize > 2048 * 1024) {
                    throw new \Exception('Hình ảnh không được vượt quá 2mb');
                }

                $file->move($destination, $filename);
                $image->image_url = $filename;
            }

            $image->product_id = $request->product_id;
            $image->save();

            return response()->json(['success' => true, 'message' => 'Ảnh đã được cập nhật thành công!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found with ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh với ID: ' . $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in ImageController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $image = Image::findOrFail($id);

            $path = public_path('assets/images/' . $image->image_url);
            if (file_exists($path) && is_writable($path)) {
                unlink($path);
            } else {
                Log::warning("Cannot delete file: $path");
            }

            $image->delete();

            return response()->json(['success' => true, 'message' => 'Ảnh đã được xóa thành công!']);
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found with ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh với ID: ' . $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in ImageController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
