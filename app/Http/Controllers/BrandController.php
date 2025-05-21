<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::paginate(10);
            Log::info('Fetching brands list', ['count' => $brands->count()]);
            return view('admin.quanlybrand', compact('brands'));
        } catch (\Exception $e) {
            Log::error('Error fetching brands list: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Không thể tải danh sách thương hiệu: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Fetching brand with ID: ' . $id);
            $brand = Brand::findOrFail($id);
            Log::info('Brand found', $brand->toArray());
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $brand->name ?? '',
                    'slug' => $brand->slug ?? '',
                    'logo_url' => $brand->logo_url ?? ''
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Brand not found: ' . $id);
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thương hiệu'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate($this->brandRules());
            Log::info('Store brand request data', $request->all());

            $data = [
                'name' => $validated['name'],
                'slug' => Str::slug($validated['slug']),
                'logo_url' => $this->handleLogoUpload($request)
            ];

            $brand = Brand::create($data);
            Log::info('Brand created successfully', $brand->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu đã được thêm thành công'
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Validation errors in store', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing brand: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate($this->brandRules(true, $id));
            Log::info('Update brand request data for ID: ' . $id, $request->all());

            $brand = Brand::findOrFail($id);

            $data = [
                'name' => $validated['name'] ?? $brand->name,
                'slug' => Str::slug($validated['slug'] ?? $brand->slug),
                'logo_url' => $request->hasFile('logo')
                    ? $this->handleLogoUpload($request, $brand->logo_url)
                    : ($request->input('logo_url') ?: $brand->logo_url)
            ];

            $brand->update($data);
            Log::info('Brand updated successfully', $brand->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu đã được cập nhật thành công'
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Brand not found for update: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thương hiệu'
            ], 404);
        } catch (ValidationException $e) {
            Log::error('Validation errors in update', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating brand: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            Log::info('Deleting brand with ID: ' . $id);

            if ($brand->logo_url) {
                $path = public_path('assets/images/' . $brand->logo_url);
                if (file_exists($path)) {
                    unlink($path);
                    Log::info('Deleted logo file from public path: ' . $path);
                }
            }

            $brand->delete();
            Log::info('Brand deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu đã được xóa thành công'
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Brand not found for delete: ' . $id);
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thương hiệu'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting brand: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }

    private function brandRules($isUpdate = false, $id = null)
    {
        return [
            'name' => $isUpdate
                ? ['sometimes', 'string', 'max:255', 'unique:brands,name,' . $id]
                : ['required', 'string', 'max:255', 'unique:brands,name'],
            'slug' => $isUpdate
                ? ['sometimes', 'string', 'max:255', 'unique:brands,slug,' . $id]
                : ['required', 'string', 'max:255', 'unique:brands,slug'],
            'logo' => $isUpdate ? ['nullable', 'file', 'image', 'max:2048'] : ['required', 'file', 'image', 'max:2048'],
            'logo_url' => ['sometimes', 'string', 'max:255']
        ];
    }

    private function handleLogoUpload(Request $request, $existingLogo = null)
    {
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            if ($existingLogo) {
                $oldPath = public_path('assets/images/' . $existingLogo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                    Log::info('Deleted existing logo from public path: ' . $oldPath);
                }
            }

            $file = $request->file('logo');
            $filename = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('assets/images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            Log::info('Uploaded new logo to public path: ' . $filename);

            return $filename;
        }

        return $existingLogo;
    }
}
