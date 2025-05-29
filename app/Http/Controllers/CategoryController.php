<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        try {
            // Lấy tham số page từ request, mặc định là 1
            $page = $request->query('page', 1);

            // Kiểm tra xem page có phải là số nguyên dương không
            if (!is_numeric($page) || $page < 1) {
                return redirect()->route('admin.categories')
                    ->with('error', 'Trang không hợp lệ, đã chuyển về trang đầu tiên.');
            }

            $categories = Category::latestPaginated(2);

            // Kiểm tra nếu page vượt quá tổng số trang
            if ($page > $categories->lastPage()) {
                return redirect()->route('admin.categories', ['page' => $categories->lastPage()])
                    ->with('error', 'Trang yêu cầu không tồn tại, đã chuyển đến trang cuối cùng.');
            }

            return view('admin.quanlydanhmuc', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error in category index: ' . $e->getMessage());
            return redirect()->route('admin.categories')
                ->with('error', 'Lỗi hệ thống, vui lòng thử lại sau.');
        }
    }

    /**
     * Show the specified category.
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'category_name' => $category->category_name ?? '',
                    'description' => $category->description ?? '',
                    'updated_at' => $category->updated_at ? $category->updated_at->toDateTimeString() : ''
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Category not found: ' . $id);
            return response()->json(['success' => false, 'message' => 'Không tìm thấy danh mục'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching category: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate($this->categoryRules());
            Log::info('Store category request data:', $request->all());

            Category::create([
                'category_name' => $validated['category_name'],
                'description' => $validated['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Danh mục đã được thêm thành công'
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation errors in store: ', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate($this->categoryRules(true, $id));
            Log::info('Update category request data:', $request->all());

            $category = Category::findOrFail($id);

            // Kiểm tra khóa lạc quan với updated_at
            $clientUpdatedAt = $request->input('updated_at');
            if ($clientUpdatedAt && $category->updated_at->toDateTimeString() !== $clientUpdatedAt) {
                Log::warning('Optimistic locking conflict for category: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Danh mục đã được chỉnh sửa bởi người dùng khác. Vui lòng làm mới dữ liệu.'
                ], 409); // HTTP 409 Conflict
            }

            $data = [
                'category_name' => $request->input('category_name', $category->category_name),
                'description' => $request->input('description', $category->description),
            ];

            Log::info('Data to update:', $data);

            // Cập nhật và kiểm tra thay đổi
            $category->fill($data);
            if ($category->isDirty()) {
                $category->save();
                Log::info('Category updated successfully:', $category->toArray());
                return response()->json([
                    'success' => true,
                    'message' => 'Danh mục đã được cập nhật thành công'
                ]);
            } else {
                Log::info('No changes made to category:', $category->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Không có thay đổi nào được thực hiện'
                ]);
            }
        } catch (ModelNotFoundException $e) {
            Log::error('Category not found for update: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        } catch (ValidationException $e) {
            Log::error('Validation errors in update: ', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Danh mục đã được xóa thành công']);
        } catch (ModelNotFoundException $e) {
            Log::error('Category not found for delete: ' . $id);
            return response()->json(['success' => false, 'message' => 'Không tìm thấy danh mục'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validation rules for category.
     */
    private function categoryRules($isUpdate = false, $id = null)
    {
        $rules = [
            'category_name' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'min:2',
                'max:255',
                $isUpdate ? \Illuminate\Validation\Rule::unique('categories')->ignore($id, 'category_id') : 'unique:categories,category_name'
            ],
            'description' => 'nullable|string|min:10|max:255',
            'updated_at' => [
                $isUpdate ? 'required' : 'nullable',
                'date'
            ]
        ];

        return $rules;
    }
}
