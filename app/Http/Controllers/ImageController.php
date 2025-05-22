<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImageController extends Controller
{
    /**
     * Display a listing of the images.
     */
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

    /**
     * Show the form for creating a new image.
     */
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

    /**
     * Store a newly created image in public/assets/images.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048',
                'product_id' => 'required|integer|exists:products,product_id',
            ]);

            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('assets/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $fileSize = $file->getSize();
            if ($fileSize > 2048 * 1024) {
                throw new \Exception('Kích thước file vượt quá giới hạn 2MB');
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

    /**
     * Display the specified image.
     */
    public function show($id)
    {
        try {
            $image = Image::findOrFail($id); // Use withTrashed() if soft-deleted images should be accessible
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

    /**
     * Show the form for editing the specified image.
     */
    public function edit($id)
    {
        try {
            $image = Image::findOrFail($id); // Use withTrashed() if needed
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

    /**
     * Update the specified image in public/assets/images.
     */
    public function update(Request $request, $id)
    {
        try {
            $image = Image::findOrFail($id); // Use withTrashed() if needed

            $request->validate([
                'image' => 'image|max:2048',
                'product_id' => 'required|integer|exists:products,product_id',
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
                    throw new \Exception('Kích thước file vượt quá giới hạn 2MB');
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

    /**
     * Remove the specified image from public/assets/images.
     */
    public function destroy($id)
    {
        try {
            $image = Image::findOrFail($id); // Use withTrashed() if restoring soft-deleted images is needed

            $path = public_path('assets/images/' . $image->image_url);
            if (file_exists($path) && is_writable($path)) {
                unlink($path);
            } else {
                Log::warning("Cannot delete file: $path");
            }

            $image->delete(); // Soft delete if SoftDeletes trait is used

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
