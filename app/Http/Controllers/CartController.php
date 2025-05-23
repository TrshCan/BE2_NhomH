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

        $user = Auth::user();
        $cart = $user->cart ? $user->cart->loadAndCleanItems() : ['cart' => null, 'removedItems' => 0];

        if ($cart['removedItems'] > 0) {
            $request->session()->flash('info', "Đã xóa {$cart['removedItems']} sản phẩm không còn tồn tại khỏi giỏ hàng.");
        }

        if (!$cart['cart']) {
            return view('cart.cart', ['cart' => null])->with('error', 'Có lỗi xảy ra khi tải giỏ hàng. Vui lòng thử lại.');
        }

        return view('cart.cart', ['cart' => $cart['cart']]);
    }

    public function add(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('products.home')->with('error_auth', 'Bạn cần đăng nhập để tiếp tục.');
        }

        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->addProduct($id);

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

        $result = $cart->removeProduct($product_id);

        return match ($result) {
            'decremented' => redirect()->route('cart.cart')->with('success', 'Giảm số lượng sản phẩm thành công.'),
            'deleted'     => redirect()->route('cart.cart')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.'),
            default       => redirect()->route('cart.cart')->with('error', 'Sản phẩm không có trong giỏ hàng.'),
        };
    }

    public function updateQuantity(Request $request, $product_id)
    {
        if (!$request->isMethod('post')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Phương thức không được hỗ trợ. Vui lòng sử dụng POST.'
            ], 405);
        }

        $request->validate([
            'qty' => 'required|integer|min:1',
            'updated_at' => 'required|string',
        ], [
            'qty.required' => 'Số lượng là bắt buộc.',
            'qty.integer' => 'Số lượng phải là số nguyên.',
            'qty.min' => 'Số lượng không hợp lệ.',
            'updated_at.required' => 'Timestamp cập nhật là bắt buộc.',
        ]);

        $cart = Auth::user()->cart;

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Giỏ hàng không tồn tại.'
            ], 404);
        }

        $result = $cart->updateProductQuantityIfUnchanged($product_id, $request->qty, $request->updated_at);

        return response()->json($result, $result['status'] === 'error' && isset($result['latest_updated_at']) ? 409 : 200);
    }
}
