<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CouponManagementController extends Controller
{
    //
    public function index(Request $request)
    {
        // Nếu chưa đăng nhập
        if (!Auth::check()) {
            return redirect('login')->with('error_admin', 'Bạn cần đăng nhập và có quyền admin để truy cập.');
        }

        // Nếu đã đăng nhập nhưng không phải admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('login')->with('error_admin', 'Bạn không có quyền truy cập trang quản trị. Đã đăng xuất.');
        }

        $query = Coupon::query();

        // Handle search query
        $coupons = Coupon::filter($request->only('search'))->latest()->paginate(10);

        return view('admin.coupon-management', compact('coupons'));
    }

    public function show($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return response()->json([
                'success' => true,
                'coupon' => $coupon,
                'updated_at' => $coupon->updated_at->toIso8601String(), // Include updated_at
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin mã giảm giá.',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|unique:coupons,code|max:50',
                'type' => 'required|in:percent,fixed',
                'value' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        Coupon::validateValue($value, $request->input('type'), $fail);
                    },
                ],
                'is_active' => 'boolean',
            ]);

            $coupon = Coupon::create([
                'code' => $validated['code'],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được tạo thành công.',
                'coupon' => $coupon,
                'updated_at' => $coupon->updated_at->toIso8601String(),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating coupon: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo mã giảm giá.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:coupons,code,' . $id,
                'type' => 'required|in:percent,fixed',
                'value' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        Coupon::validateValue($value, $request->input('type'), $fail);
                    },
                ],
                'is_active' => 'boolean',
                'updated_at' => 'required|date',
            ]);

            if ($coupon->updated_at->toIso8601String() !== $validated['updated_at']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu.',
                ], 409);
            }

            $coupon->update([
                'code' => $validated['code'],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'is_active' => $validated['is_active'] ?? $coupon->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được cập nhật thành công.',
                'updated_at' => $coupon->fresh()->updated_at->toIso8601String(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating coupon: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật mã giảm giá.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $request = request();
            $coupon = Coupon::findOrFail($id);

            // Validate updated_at in request
            $validated = $request->validate([
                'updated_at' => 'required|date', // Require updated_at in request
            ]);

            // Check if updated_at matches
            if ($coupon->updated_at->toIso8601String() !== $validated['updated_at']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu.',
                ], 409); // 409 Conflict
            }

            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được xóa thành công.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error deleting coupon: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error deleting coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa mã giảm giá.',
            ], 500);
        }
    }
}
