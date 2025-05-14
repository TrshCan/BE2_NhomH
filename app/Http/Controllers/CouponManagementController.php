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
        if ($request->has('search') && $request->input('search') !== '') {
            $search = $request->input('search');
            $query->where('code', 'like', "%{$search}%");
        }

        $coupons = $query->latest()->paginate(10); // 10 coupons per page

        return view('admin.coupon-management', compact('coupons'));
    }

    public function show($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return response()->json([
                'success' => true,
                'coupon' => $coupon,
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
                'value' => 'required|numeric|min:0',
                'is_active' => 'boolean',
            ]);

            $coupon = Coupon::create([
                'code' => $validated['code'],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'is_active' => $validated['is_active'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được tạo thành công.',
                'coupon' => $coupon,
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
                'value' => 'required|numeric|min:0',
                'is_active' => 'boolean',
            ]);

            $coupon->update([
                'code' => $validated['code'],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'is_active' => $validated['is_active'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được cập nhật thành công.',
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
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được xóa thành công.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa mã giảm giá.',
            ], 500);
        }
    }
}