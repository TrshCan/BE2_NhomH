<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        $cartItems = $cart ? $cart->items : collect();

        $subtotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        return view('cart.checkout', compact('user', 'cartItems', 'subtotal'));
    }


    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'payment' => 'required|string',
            'total' => 'required|numeric',
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.cart')->with('error', 'Giỏ hàng trống.');
        }

        DB::beginTransaction();

        try {
            $shippingAddress = "{$request->address}, {$request->ward}, {$request->district}, {$request->province}";

            $order = Order::create([
                'user_id' => $user->id,
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

            // Clear the cart
            $cart->products()->detach();

            DB::commit();

            return redirect()->route('products.home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi đặt hàng.')->withInput();
        }
    }
}
