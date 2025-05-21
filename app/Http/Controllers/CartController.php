<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Order;

class CartController extends Controller
{
    public function viewCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('products.home')->with('error_auth', 'Bạn cần đăng nhập để tiếp tục.');
        }


        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        return view('cart.cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('products.home')->with('error_auth', 'Bạn cần đăng nhập để tiếp tục.');
        }

        $user = Auth::user();

        // Find or create a cart for the current user
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['created_at' => now()]
        );

        // Check if the product is already in the cart
        $cartItem = CartProduct::where('cart_id', $cart->cart_id)
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            // If product already in cart, increment quantity
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // Add new item to cart
            CartProduct::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        // Redirect back to the product page (or wherever you want to send the user)
        return redirect()->route('products.home')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function clear()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            CartProduct::where('cart_id', $cart->cart_id)->delete();
            return redirect()->route('cart.cart')->with('success', 'Giỏ hàng đã được xóa thành công.');
        }

        return redirect()->route('cart.cart')->with('error', 'Không tìm thấy giỏ hàng.');
    }

    public function remove($product_id)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart.cart')->with('error', 'Giỏ hàng không tồn tại.');
        }

        $item = CartProduct::where('cart_id', $cart->cart_id)
            ->where('product_id', $product_id)
            ->first();

        if ($item) {
            if ($item->quantity > 1) {
                $item->quantity -= 1;
                $item->save();
                $message = 'Giảm số lượng sản phẩm thành công.';
            } else {
                $item->delete();
                $message = 'Sản phẩm đã được xóa khỏi giỏ hàng.';
            }

            return redirect()->route('cart.cart')->with('success', $message);
        }

        return redirect()->route('cart.cart')->with('error', 'Sản phẩm không có trong giỏ hàng.');
    }

    public function updateQuantity($product_id, $qty)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart && $qty >= 1) {
            $cartItem = CartProduct::where('cart_id', $cart->cart_id)
                ->where('product_id', $product_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity = $qty;
                $cartItem->save();
                return redirect()->route('cart.cart')->with('success', 'Cập nhật số lượng thành công.');
            }
        }

        return redirect()->route('cart.cart')->with('error', 'Có lỗi xảy ra khi cập nhật số lượng.');
    }
}
