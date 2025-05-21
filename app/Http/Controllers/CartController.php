<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;

class CartController extends Controller
{
    public function viewCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('products.home')->with('error_auth', 'Bạn cần đăng nhập để tiếp tục.');
        }

        $cart = Auth::user()->cart()->with('items.product')->first();

        return view('cart.cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('products.home')->with('error_auth', 'Bạn cần đăng nhập để tiếp tục.');
        }

        $user = Auth::user();

        $cart = $user->cart ?? $user->cart()->create();

        $item = $cart->items()->where('product_id', $id)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('products.home')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function clear()
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $cart->items()->delete();
            return redirect()->route('cart.cart')->with('success', 'Giỏ hàng đã được xóa thành công.');
        }

        return redirect()->route('cart.cart')->with('error', 'Không tìm thấy giỏ hàng.');
    }

    public function remove($product_id)
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            return redirect()->route('cart.cart')->with('error', 'Giỏ hàng không tồn tại.');
        }

        $item = $cart->items()->where('product_id', $product_id)->first();

        if ($item) {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
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
        if ($qty < 1) {
            return redirect()->route('cart.cart')->with('error', 'Số lượng không hợp lệ.');
        }

        $cart = Auth::user()->cart;

        $item = $cart?->items()->where('product_id', $product_id)->first();

        if ($item) {
            $item->update(['quantity' => $qty]);
            return redirect()->route('cart.cart')->with('success', 'Cập nhật số lượng thành công.');
        }

        return redirect()->route('cart.cart')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }
}
