<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; 
use App\Models\Promotion;
use App\Models\Product;   
use App\Models\Coupon;  

class CouponController extends Controller
{
    public function validate(Request $request)
    {
        $code = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
        }
        if ($coupon->is_active === 0) {
            return response()->json(['valid' => false, 'message' => 'Mã giảm giá đã hết hạn.']);
        }
        $discountAmount = $coupon->type === 'percent'
            ? $request->subtotal * ($coupon->value / 100)
            : $coupon->value;

        return response()->json([
            'valid' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount' => $discountAmount
        ]);
    }
}
