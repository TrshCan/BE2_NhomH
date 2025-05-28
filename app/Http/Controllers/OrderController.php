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

            // Add coupon to session with updated_at
            $appliedCoupons[$coupon->code] = [
                'discount' => $discount,
                'updated_at' => $coupon->updated_at->toDateTimeString(),
            ];
            Session::put('applied_coupons', $appliedCoupons);

            $totalDiscount = array_sum(array_column($appliedCoupons, 'discount'));
            $total = $subtotal - $totalDiscount;

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá được áp dụng thành công.',
                'coupon' => [
                    'code' => $coupon->code,
                    'discount' => $discount,
                    'updated_at' => $coupon->updated_at->toDateTimeString(),
                ],
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
            $user = Auth::user()->load('status');

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên đăng nhập đã hết hạn hoặc tài khoản không còn tồn tại. Vui lòng đăng nhập lại.',
                ], 401);
            }

            if ($user->status_id === 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản bị khóa không thể thanh toán',
                ], 401);
            }

            $cart = $user->cart()->with('items.product')->first();
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng của bạn đang trống nhaaaaa.',
                ], 422);
            }

            $subtotal = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
            $appliedCoupons = Session::get('applied_coupons', []);
            $validCoupons = [];
            $totalDiscount = 0;

            // ✅ Validate coupons in separate try-catch
            try {
                foreach ($appliedCoupons as $couponCode => $stored) {
                    $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();

                    if (
                        $coupon &&
                        (!isset($stored['updated_at']) || $stored['updated_at'] === $coupon->updated_at->toDateTimeString())
                    ) {
                        $couponDiscount = $coupon->type === 'percent'
                            ? ($subtotal * $coupon->value) / 100
                            : min($coupon->value, $subtotal);

                        $validCoupons[] = [
                            'code' => $couponCode,
                            'discount' => $couponDiscount,
                            'updated_at' => $coupon->updated_at->toDateTimeString(),
                        ];

                        $totalDiscount += $couponDiscount;
                    }
                }

                // Update session coupons if changed
                $newCoupons = [];
                foreach ($validCoupons as $coupon) {
                    $newCoupons[$coupon['code']] = [
                        'discount' => $coupon['discount'],
                        'updated_at' => $coupon['updated_at'],
                    ];
                }
                Session::put('applied_coupons', $newCoupons);
            } catch (\Exception $e) {
                Log::error('Error validating coupons: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi kiểm tra mã giảm giá.',
                ], 500);
            }

            $total = $subtotal - $totalDiscount;

            // ✅ Check stock availability in separate try-catch
            try {
                foreach ($cart->items as $item) {
                    $product = $item->product;
                    $cartQuantity = $item->quantity;

                    $pendingOrdersQuantity = DB::table('order_details')
                        ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
                        ->where('orders.status', 'pending')
                        ->where('order_details.product_id', $product->id)
                        ->sum('order_details.quantity');

                    $totalRequested = $cartQuantity + $pendingOrdersQuantity;

                    if ($totalRequested > $product->stock_quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => "Sản phẩm '{$product->product_name}' không đủ số lượng trong kho.",
                            'details' => [
                                'product_id' => $product->product_id,
                                'name' => $product->product_name,
                                'stock_quantity' => $product->stock_quantity,
                                'cart_quantity' => $cartQuantity,
                                'pending_orders_quantity' => $pendingOrdersQuantity,
                                'total_requested' => $totalRequested,
                            ],
                        ], 422);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error checking stock: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi kiểm tra số lượng tồn kho.',
                ], 500);
            }

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

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Phiên đăng nhập đã hết hạn hoặc tài khoản của bạn không còn tồn tại. Vui lòng đăng nhập lại.',
            ], 401);
        }

        $cart = $user->cart()->with('products')->first();
        if (!$cart || $cart->products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Giỏ hàng của bạn đang trống đấy nha.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $subtotal = $cart->products->sum(fn($product) => $product->price * $product->pivot->quantity);
            $appliedCoupons = Session::get('applied_coupons', []);
            $validCoupons = [];
            $totalDiscount = 0;

            foreach ($appliedCoupons as $couponCode => $stored) {
                $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
                if ($coupon && (!isset($stored['updated_at']) || $stored['updated_at'] === $coupon->updated_at->toDateTimeString())) {
                    $couponDiscount = $coupon->type === 'percent'
                        ? ($subtotal * $coupon->value) / 100
                        : min($coupon->value, $subtotal);
                    $validCoupons[$couponCode] = [
                        'discount' => $couponDiscount,
                        'updated_at' => $coupon->updated_at->toDateTimeString(),
                    ];
                    $totalDiscount += $couponDiscount;
                } else {
                    Log::warning("Coupon {$couponCode} is no longer valid during checkout for user {$user->id}");
                }
            }

            if ($appliedCoupons != $validCoupons) {
                Session::put('applied_coupons', $validCoupons);
            }

            $total = max(0, round($subtotal - $totalDiscount, 2));

            if (abs(round($request->total, 2) - $total) > 0.1) {
                Log::error('Total mismatch during checkout', [
                    'user_id' => $user->id,
                    'request_total' => $request->total,
                    'calculated_total' => $total,
                    'subtotal' => $subtotal,
                    'total_discount' => $totalDiscount,
                    'applied_coupons' => $appliedCoupons,
                    'validCoupons' => $validCoupons,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tổng số tiền không hợp lệ do thay đổi trong giỏ hàng hoặc mã giảm giá. Vui lòng kiểm tra lại.',
                ], 400);
            }

            $shippingAddress = "{$request->address}, {$request->ward}, {$request->district}, {$request->province}";

            $order = $user->orders()->create([
                'order_date' => now(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($cart->products as $product) {
                $order->orderdetails()->create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                ]);
            }

            foreach (array_keys($validCoupons) as $couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $order->coupons()->attach($coupon->id, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $cart->items()->delete();
            $cart->delete();
            Session::forget('applied_coupons');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Đặt hàng thành công!',
                'data' => [
                    'order_id' => $order->id,
                    'total_amount' => $total,
                ],
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing order: ' . $e->getMessage(), ['user_id' => $user->id ?? null]);
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại.',
            ], 500);
        }
    }

    public function getOrderDetails($orderId)
    {
        try {
            $order = Order::with('orderdetails.product')
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
