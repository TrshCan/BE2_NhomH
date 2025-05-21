<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product')->first();
        $cartItems = $cart ? $cart->items : collect();

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $appliedCoupons = Session::get('applied_coupons', []);
        $couponDiscount = array_sum($appliedCoupons); // Sum of all coupon discounts
        $total = $subtotal - $couponDiscount;

        return view('cart.checkout', compact('user', 'cartItems', 'subtotal', 'couponDiscount', 'total'));
    }

    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
            ]);

            $coupon = Coupon::isActive($request->coupon_code);

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hiệu lực.',
                ], 422);
            }

            $appliedCoupons = Session::get('applied_coupons', []);

            if (isset($appliedCoupons[$coupon->code])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã được áp dụng.',
                ], 422);
            }

            $user = Auth::user();
            $cart = $user->cart()->with('items.product')->first();
            $cartItems = $cart ? $cart->items : collect();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

            $discount = $coupon->type === 'percent'
                ? ($subtotal * $coupon->value) / 100
                : min($coupon->value, $subtotal);

            // Add coupon to session
            $appliedCoupons[$coupon->code] = $discount;
            Session::put('applied_coupons', $appliedCoupons);

            $totalDiscount = array_sum($appliedCoupons);
            $total = $subtotal - $totalDiscount;

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được áp dụng thành công.',
                'coupon' => ['code' => $coupon->code, 'discount' => $discount],
                'subtotal' => $subtotal,
                'total_discount' => $totalDiscount,
                'total' => $total,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error applying coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi áp dụng mã giảm giá.',
            ], 500);
        }
    }

    public function removeCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
            ]);

            $couponCode = $request->coupon_code;
            $appliedCoupons = Session::get('applied_coupons', []);

            if (!isset($appliedCoupons[$couponCode])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không được áp dụng.',
                ], 422);
            }

            // Remove specific coupon
            unset($appliedCoupons[$couponCode]);
            Session::put('applied_coupons', $appliedCoupons);

            $user = Auth::user();
            $cart = $user->cart()->with('items.product')->first();
            $cartItems = $cart ? $cart->items : collect();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $totalDiscount = array_sum($appliedCoupons);
            $total = $subtotal - $totalDiscount;

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá đã được xóa.',
                'subtotal' => $subtotal,
                'total_discount' => $totalDiscount,
                'total' => $total,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error removing coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa mã giảm giá.',
            ], 500);
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'payment' => 'required|string',
            'total' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $cart = $user->cart()->with('products')->first();

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.cart')->with('error', 'Giỏ hàng trống.');
        }

        DB::beginTransaction();

        try {
            $shippingAddress = "{$request->address}, {$request->ward}, {$request->district}, {$request->province}";

            $order = $user->orders()->create([
                'order_date' => now(),
                'total_amount' => $request->total,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($cart->products as $product) {
                $order->details()->create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                ]);
            }

            $appliedCoupons = Session::get('applied_coupons', []);
            if (!empty($appliedCoupons)) {
                foreach (array_keys($appliedCoupons) as $couponCode) {
                    $coupon = Coupon::where('code', $couponCode)->first();
                    if ($coupon) {
                        $order->coupons()->attach($coupon->id, [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                Session::forget('applied_coupons');
            }

            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('products.home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing order: ' . $e->getMessage());
            return redirect()->route('products.home')->with('error_auth', 'Đã xảy ra lỗi khi đặt hàng! ' . $e->getMessage());
        }
    }
}