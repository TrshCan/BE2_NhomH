<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CouponManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('login')->with('error_admin', 'Bạn cần đăng nhập và có quyền admin để truy cập.');
        }

        // Nếu đã đăng nhập nhưng không phải admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('login')->with('error_admin', 'Bạn không có quyền truy cập trang quản trị. Đã đăng xuất.');
        }
        $coupons = Coupon::filter($request->only('search'))->latest()->paginate(10);
        return view('admin.coupon-management', compact('coupons'));
    }

    public function show($id)
    {
        return $this->jsonResponse(Coupon::findForShow($id));
    }

    public function store(Request $request)
    {
        return $this->jsonResponse(Coupon::createWithValidation($request->all()), 201);
    }

    public function update(Request $request, $id)
    {
        try {
            // Use the model's findForUpdate method
            $result = Coupon::findForUpdate($id);

            // If result is an array, it's an error response
            if (is_array($result)) {
                return $this->jsonResponse($result, isset($result['success']) && !$result['success'] ? 400 : 200);
            }

            // Proceed with update if a coupon instance is returned
            return $this->jsonResponse($result->updateWithValidation($request->all()));
        } catch (\Exception $e) {
            Log::error('Unexpected error in update coupon: ' . $e->getMessage(), [
                'coupon_id' => $id,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Lỗi hệ thống khi cập nhật mã giảm giá: Mã không tồn tại hoặc đã bị xóa.',
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        return $this->jsonResponse($coupon->deleteWithValidation($request->input('updated_at')));
    }

    private function jsonResponse($result, $successCode = 200)
    {
        $code = match (true) {
            !$result['success'] && str_contains($result['message'], 'chỉnh sửa bởi người khác') => 409,
            !$result['success'] && isset($result['errors']) => 422,
            !$result['success'] => 500,
            default => $successCode,
        };
        return response()->json($result, $code);
    }
}
