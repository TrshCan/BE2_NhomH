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

    public function validate(Request $request)
    {
        try {
            $user = Auth::user()->with('status')->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên đăng nhập đã hết hạn hoặc tài khoản không còn tồn tại. Vui lòng đăng nhập lại.',
                ], 401);
            }
            if ($user->status_id === 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản bị khóa không thể thanh toán.',
                ], 401);
            }

            $cart = $user->cart()->with('items.product')->first();
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng của bạn đang trống.',
                ], 422);
            }

            $subtotal = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
            $appliedCoupons = Session::get('applied_coupons', []);
            $validCoupons = [];
            $totalDiscount = 0;

            // Validate applied coupons
            foreach ($appliedCoupons as $couponCode => $discount) {
                $coupon = Coupon::isActive($couponCode);
                if ($coupon) {
                    $couponDiscount = $coupon->type === 'percent'
                        ? ($subtotal * $coupon->value) / 100
                        : min($coupon->value, $subtotal);
                    $validCoupons[] = ['code' => $couponCode, 'discount' => $couponDiscount];
                    $totalDiscount += $couponDiscount;
                }
            }

            // Update session if any coupons are invalid
            if (count($appliedCoupons) != count($validCoupons)) {
                $newCoupons = [];
                foreach ($validCoupons as $coupon) {
                    $newCoupons[$coupon['code']] = $coupon['discount'];
                }
                Session::put('applied_coupons', $newCoupons);
            }

            $total = $subtotal - $totalDiscount;

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'total_discount' => $totalDiscount,
                'total' => $total,
                'applied_coupons' => $validCoupons,
            ]);
        } catch (\Exception $e) {
            Log::error('Error validating checkout: ' . $e->getMessage(), ['user_id' => Auth::id() ?? null]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi kiểm tra giỏ hàng.',
            ], 500);
        }
    }

    public function process(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'payment' => 'required|string',
            'total' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        // Check if user is still authenticated
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Phiên đăng nhập đã hết hạn hoặc tài khoản của bạn không còn tồn tại. Vui lòng đăng nhập lại.');
        }

        // Check if cart exists and is not empty
        $cart = $user->cart()->with('products')->first();
        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        DB::beginTransaction();

        try {
            // Calculate subtotal from cart
            $subtotal = $cart->products->sum(fn($product) => $product->price * $product->pivot->quantity);
            $appliedCoupons = Session::get('applied_coupons', []);
            $validCoupons = [];
            $totalDiscount = 0;

            // Validate applied coupons
            foreach ($appliedCoupons as $couponCode => $discount) {
                $coupon = Coupon::isActive($couponCode);
                if ($coupon) {
                    // Recalculate discount to ensure it matches current coupon rules
                    $couponDiscount = $coupon->type === 'percent'
                        ? ($subtotal * $coupon->value) / 100
                        : min($coupon->value, $subtotal);
                    $validCoupons[$couponCode] = $couponDiscount;
                    $totalDiscount += $couponDiscount;
                } else {
                    // Log invalid coupon and notify user later
                    Log::warning("Coupon {$couponCode} is no longer valid during checkout for user {$user->id}");
                }
            }

            // Update session with valid coupons only
            if ($appliedCoupons != $validCoupons) {
                Session::put('applied_coupons', $validCoupons);
                Session::flash('warning', 'Một hoặc nhiều mã giảm giá đã hết hiệu lực và đã được xóa.');
            }

            // Calculate final total
            $total = $subtotal - $totalDiscount;

            // Verify the total matches the request to prevent tampering
            if (abs($request->total - $total) > 0.01) {
                return redirect()->route('cart.checkout')
                    ->with('error', 'Tổng số tiền không hợp lệ do thay đổi trong giỏ hàng hoặc mã giảm giá. Vui lòng kiểm tra lại.');
            }

            // Create shipping address
            $shippingAddress = "{$request->address}, {$request->ward}, {$request->district}, {$request->province}";

            // Create order
            $order = $user->orders()->create([
                'order_date' => now(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
            ]);

            // Create order details
            foreach ($cart->products as $product) {
                $order->details()->create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                ]);
            }

            // Attach valid coupons to the order
            foreach (array_keys($validCoupons) as $couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $order->coupons()->attach($coupon->id, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Clear cart and session
            $cart->items()->delete();
            $cart->delete();
            Session::forget('applied_coupons');

            DB::commit();

            return redirect()->route('products.home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing order: ' . $e->getMessage(), ['user_id' => $user->id ?? null]);
            return redirect()->route('cart.checkout')->with('error', 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại.');
        }
    }

    public function getOrderDetails($orderId)
    {
        try {
            // Fetch the order with its details and related products, ensuring it belongs to the authenticated user
            $order = Order::with('details.product')
                ->where('user_id', Auth::id())
                ->where('order_id', $orderId)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không tồn tại hoặc đã bị xóa.'
                ], 404);
            }

            // Format the order data for the response
            return response()->json([
                'success' => true,
                'order_id' => $order->order_id,
                'user' => [
                    'name' => $order->user->name ?? 'N/A'
                ],
                'order_date' => $order->order_date->toDateTimeString(),
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'shipping_address' => $order->shipping_address,
                'details' => $order->details->map(function ($detail) {
                    return [
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->product_name ?? 'N/A',
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                    ];
                })->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching order details: ' . $e->getMessage(), ['order_id' => $orderId]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải chi tiết đơn hàng.'
            ], 500);
        }
    }
}
