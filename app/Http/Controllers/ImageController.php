<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page = $request->query('page', 1); // Default to page 1
            $perPage = 10; // Images per page

            // Validate page parameter
            if (!is_numeric($page) || $page < 1) {
                return redirect()->route('admin.images.index')
                    ->with('error', 'Tham số trang không hợp lệ. Vui lòng thử lại.');
            }

            // Build query
            $query = Image::with('product');

            // Check maximum pages
            $totalImages = $query->count();
            $maxPages = ceil($totalImages / $perPage);

            if ($page > $maxPages && $totalImages > 0) {
                return redirect()->route('admin.images.index', ['page' => $maxPages])
                    ->with('error', 'Trang bạn yêu cầu không tồn tại. Đã chuyển đến trang cuối cùng.');
            }

            // Perform pagination
            $images = $query->paginate($perPage)->withQueryString();
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
                'product_id.required' => 'Không được để trống',
                'product_id.integer' => 'ID sản phẩm phải là số nguyên',
                'product_id.exists' => 'ID sản phẩm không tồn tại'
            ]);

            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Kiểm tra xem hình ảnh với product_id và image_url đã tồn tại chưa
            if (Image::where('product_id', $request->product_id)->where('image_url', $filename)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hình ảnh với tên này đã tồn tại cho sản phẩm này. Vui lòng chọn hình ảnh khác.'
                ], 422);
            }

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
            if (!$image->updated_at) {
                Log::warning('Image record missing updated_at', ['image_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Bản ghi không có thông tin updated_at. Vui lòng kiểm tra dữ liệu.'
                ], 500);
            }
            $imageData = [
                'id' => $image->id,
                'product_id' => $image->product_id,
                'image_url' => $image->image_url,
                'updated_at' => $image->updated_at->format('Y-m-d H:i:s'),
            ];
            Log::info('Image data in show method', [
                'id' => $id,
                'updated_at' => $imageData['updated_at']
            ]);
            return response()->json(['success' => true, 'data' => $imageData]);
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
            if (!$image->updated_at) {
                Log::warning('Image record missing updated_at', ['image_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Bản ghi không có thông tin updated_at. Vui lòng kiểm tra dữ liệu.'
                ], 500);
            }
            $products = Product::all();
            $imageData = [
                'id' => $image->id,
                'product_id' => $image->product_id,
                'image_url' => $image->image_url,
                'updated_at' => $image->updated_at->format('Y-m-d H:i:s'),
            ];
            Log::info('Image data in edit method', [
                'id' => $id,
                'updated_at' => $imageData['updated_at']
            ]);
            return response()->json(['success' => true, 'data' => $imageData, 'products' => $products]);
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
                'updated_at' => 'sometimes|date_format:Y-m-d H:i:s',
            ], [
                'image.image' => 'Hình ảnh phải có định dạng .jpg, .png, jpeg',
                'image.mimes' => 'Hình ảnh phải có định dạng .jpg, .png, jpeg',
                'image.max' => 'Hình ảnh không được vượt quá 2mb',
                'product_id.required' => 'Không được để trống',
                'product_id.integer' => 'ID sản phẩm phải là số nguyên',
                'product_id.exists' => 'ID sản phẩm không tồn tại',
                'updated_at.date_format' => 'Định dạng updated_at không hợp lệ. Vui lòng sử dụng định dạng Y-m-d H:i:s.',
            ]);

            // Kiểm tra xung đột dựa trên updated_at
            if ($request->has('updated_at') && $request->input('updated_at') && $image->updated_at) {
                $requestUpdatedAt = Carbon::parse($request->input('updated_at'))->format('Y-m-d H:i:s');
                $imageUpdatedAt = $image->updated_at->format('Y-m-d H:i:s');

                Log::info('Checking for update conflict', [
                    'image_id' => $id,
                    'request_updated_at' => $requestUpdatedAt,
                    'image_updated_at' => $imageUpdatedAt,
                ]);

                if ($requestUpdatedAt !== $imageUpdatedAt) {
                    Log::warning('Update conflict detected', [
                        'image_id' => $id,
                        'request_updated_at' => $requestUpdatedAt,
                        'current_updated_at' => $imageUpdatedAt,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Hình ảnh đã được chỉnh sửa bởi người dùng khác. Vui lòng tải lại trang để cập nhật dữ liệu mới nhất.'
                    ], 409);
                }
            } elseif (!$image->updated_at) {
                Log::warning('Image record missing updated_at', ['image_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Bản ghi không có thông tin updated_at. Vui lòng kiểm tra dữ liệu.'
                ], 500);
            }

            return DB::transaction(function () use ($request, $image) {
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
                $image->touch(); // Update updated_at before saving
                $image->save();

                return response()->json(['success' => true, 'message' => 'Ảnh đã được cập nhật thành công!']);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in ImageController@update: ' . json_encode($e->errors()));
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
