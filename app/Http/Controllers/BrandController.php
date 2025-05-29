<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\FilesystemException;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            if (!is_numeric($page) || $page <= 0 || floor($page) != $page) {
                return redirect()->route('admin.brands')->with('error', 'Tham số trang không hợp lệ!');
            }

            $perPage = 3;
            $brands = Brand::latest()->paginate($perPage);

            if ($page > $brands->lastPage()) {
                return redirect()->route('admin.brands', ['page' => $brands->lastPage()])
                    ->with('error', 'Trang yêu cầu không tồn tại! Đã chuyển đến trang cuối cùng.');
            }

            return view('admin.quanlybrand', compact('brands'));
        } catch (QueryException $e) {
            Log::error('Database error fetching brands: ' . $e->getMessage());
            return back()->with('error', 'Lỗi khi tải danh sách thương hiệu!');
        } catch (\Exception $e) {
            Log::error('Error fetching brands: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    }

    public function show($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $brand,
                'updated_at' => $brand->updated_at->toDateTimeString()
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thương hiệu!'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi lấy thông tin thương hiệu!'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255|unique:brands,name|regex:/^[a-zA-Z0-9\s]+$/',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Không được để trống tên thương hiệu!',
            'name.min' => 'Tên thương hiệu phải ít nhất 2 ký tự!',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự!',
            'name.unique' => 'Tên thương hiệu đã tồn tại. Vui lòng nhập tên khác!',
            'name.regex' => 'Tên thương hiệu không được chứa ký tự đặc biệt!',
            'slug.required' => 'Không được để trống slug!',
            'slug.max' => 'Slug không được vượt quá 255 ký tự!',
            'slug.unique' => 'Slug đã tồn tại. Vui lòng nhập slug khác!',
            'logo.required' => 'Vui lòng chọn ảnh cho danh mục!',
            'logo.image' => 'Hình ảnh phải có định dạng .jpg, .png, .jpeg!',
            'logo.mimes' => 'Hình ảnh phải có định dạng .jpg, .png, .jpeg!',
            'logo.max' => 'Hình ảnh không được vượt quá 2MB!',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Kiểm tra trùng lặp trước khi lưu
            if (Brand::where('name', $request->input('name'))->orWhere('slug', $request->input('slug'))->exists()) {
                return response()->json(['success' => false, 'message' => 'Thương hiệu hoặc slug đã tồn tại!'], 422);
            }

            $data = $request->only(['name', 'slug']);
            if ($request->hasFile('logo')) {
                $destinationPath = public_path('assets/images');
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                if (!is_writable($destinationPath)) {
                    throw new FilesystemException('Thư mục đích không có quyền ghi!');
                }

                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $data['logo_url'] = $filename;
            }

            Brand::create($data);
            return response()->json(['success' => true, 'message' => 'Thêm thương hiệu thành công!']);
        } catch (QueryException $e) {
            Log::error('Database error creating brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu khi thêm thương hiệu!'], 500);
        } catch (FilesystemException $e) {
            Log::error('File error creating brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi lưu file logo: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Error creating brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm thương hiệu!'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);

            Log::info('Update request for brand ID: ' . $id, [
                'request_updated_at' => $request->input('updated_at'),
                'database_updated_at' => $brand->updated_at->toDateTimeString()
            ]);

            if ($request->input('updated_at') && $brand->updated_at->toDateTimeString() !== $request->input('updated_at')) {
                Log::warning('Edit conflict on brand ID ' . $id . ': updated_at mismatch');
                return response()->json([
                    'success' => false,
                    'message' => 'Thương hiệu đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu!'
                ], 409);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255|unique:brands,name,' . $id . '|regex:/^[a-zA-Z0-9\s]+$/',
                'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'name.required' => 'Không được để trống tên thương hiệu!',
                'name.min' => 'Tên thương hiệu phải ít nhất 2 ký tự!',
                'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự!',
                'name.unique' => 'Tên thương hiệu đã tồn tại. Vui lòng nhập tên khác!',
                'name.regex' => 'Tên thương hiệu không được chứa ký tự đặc biệt!',
                'slug.required' => 'Không được để trống slug!',
                'slug.max' => 'Slug không được vượt quá 255 ký tự!',
                'slug.unique' => 'Slug đã tồn tại. Vui lòng nhập slug khác!',
                'logo.image' => 'Hình ảnh phải có định dạng .jpg, .png, .jpeg!',
                'logo.mimes' => 'Hình ảnh phải có định dạng .jpg, .png, .jpeg!',
                'logo.max' => 'Hình ảnh không được vượt quá 2MB!',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $data = $request->only(['name', 'slug']);
            if ($request->hasFile('logo')) {
                $destinationPath = public_path('assets/images');
                if (!is_writable($destinationPath)) {
                    return response()->json(['success' => false, 'message' => 'Không thể ghi file vào thư mục!'], 500);
                }

                if ($brand->logo_url && File::exists(public_path('assets/images/' . $brand->logo_url))) {
                    File::delete(public_path('assets/images/' . $brand->logo_url));
                }

                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $data['logo_url'] = $filename;
            } elseif ($request->input('logo_url')) {
                $data['logo_url'] = $request->input('logo_url');
            }

            $brand->update($data);
            return response()->json(['success' => true, 'message' => 'Cập nhật thương hiệu thành công!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thương hiệu!'], 404);
        } catch (QueryException $e) {
            Log::error('Database error updating brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu khi cập nhật!'], 500);
        } catch (FilesystemException $e) {
            Log::error('File error updating brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi xử lý file logo!'], 500);
        } catch (\Exception $e) {
            Log::error('Error updating brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật thương hiệu!'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            if ($brand->logo_url && File::exists(public_path('assets/images/' . $brand->logo_url))) {
                File::delete(public_path('assets/images/' . $brand->logo_url));
            }

            $brand->delete();
            return response()->json(['success' => true, 'message' => 'Xóa thương hiệu thành công!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thương hiệu!'], 404);
        } catch (QueryException $e) {
            Log::error('Database error deleting brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu khi xóa!'], 500);
        } catch (FilesystemException $e) {
            Log::error('File error deleting brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi xóa file logo!'], 500);
        } catch (\Exception $e) {
            Log::error('Error deleting brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa thương hiệu!'], 500);
        }
    }
}
