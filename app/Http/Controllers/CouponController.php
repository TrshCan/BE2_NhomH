<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // Adjusted to singular 'Category' per Laravel convention
use App\Models\Promotion; // Assuming you have a Promotion model
use App\Models\Product;   // Assuming you have a Product model
use App\Models\Coupon;   // Assuming you have a Product model

class CategoriesController extends Controller
{
    public function validate(Request $request)
    {
        $code = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
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
