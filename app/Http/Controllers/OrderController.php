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
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        $cartItems = $cart ? $cart->items : collect();

        $subtotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        return view('cart.checkout', compact('user', 'cartItems', 'subtotal'));
    }

    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
            ]);

            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hiệu lực.',
                ], 422);
            }

            // Check if coupon is already applied in session
            if (Session::get('applied_coupon') === $coupon->code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã được áp dụng.',
                ], 422);
            }

            // Calculate discount
            $user = Auth::user();
            $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
            $cartItems = $cart ? $cart->items : collect();
            $subtotal = $cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);

            $discount = $coupon->type === 'percent'
                ? ($subtotal * $coupon->value) / 100
                : min($coupon->value, $subtotal); // Prevent negative total

            $total = $subtotal - $discount;

            // Store coupon in session
            Session::put('applied_coupon', $coupon->code);
            Session::put('coupon_discount', $discount);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được áp dụng thành công.',
                'coupon' => [
                    'code' => $coupon->code,
                    'discount' => $discount,
                ],
                'subtotal' => $subtotal,
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
            // Clear coupon from session
            Session::forget('applied_coupon');
            Session::forget('coupon_discount');

            // Recalculate total
            $user = Auth::user();
            $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
            $cartItems = $cart ? $cart->items : collect();
            $subtotal = $cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá đã được xóa.',
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'discount' => 0,
            ]);
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
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.cart')->with('error', 'Giỏ hàng trống.');
        }

        DB::beginTransaction();

        try {
            $shippingAddress = "{$request->address}, {$request->ward}, {$request->district}, {$request->province}";

            $order = Order::create([
                'user_id' => $user->id,
                'order_date' => now(),
                'total_amount' => $request->total,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($cart->products as $product) {
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                ]);
            }

            // Apply coupon if present in session
            if (Session::has('applied_coupon')) {
                $coupon = Coupon::where('code', Session::get('applied_coupon'))->first();
                if ($coupon) {
                    // Create coupon_order record
                    DB::table('coupon_order')->insert([
                        'coupon_id' => $coupon->id,
                        'order_id' => $order->order_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                Session::forget(['applied_coupon', 'coupon_discount']);
            }

            // Clear the cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('products.home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing order: ' . $e->getMessage());
            return redirect()->route('products.home')->with('error_auth', 'Đã xảy ra lỗi khi đặt hàng!' . $e->getMessage());
        }
    }
}