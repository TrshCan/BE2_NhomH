<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        // TEMP: hardcoded user ID until auth is done
        $userId = 2;

        // Get or create the user's cart
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Ensure product exists
        $product = Product::findOrFail($productId);

        // Check if product is already in the cart
        $existing = $cart->products()->where('products.product_id', $productId)->first();

        if ($existing) {
            // Update quantity
            $cart->products()->updateExistingPivot($productId, [
                'quantity' => $existing->pivot->quantity + 1,
                'updated_at' => now(), // ensure timestamps are updated
            ]);
        } else {
            // Add new product to cart (Ensure cart_id is set)
            $cart->products()->attach($productId, [
                'cart_id' => 2,  // Ensure cart_id is set manually
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Redirect back to the cart page after adding to the cart
        return redirect()->route('products.home')->with('success', 'Product added to cart');
    }

    public function index()
    {
        // Fetch the cart and related products for the logged-in user (or hardcoded user_id 2 for now)
        $cart = Cart::with('products')->where('user_id', 2)->first();

        // If no cart exists, create an empty cart
        if (!$cart) {
            $cart = Cart::create(['user_id' => 2]);
        }

        // Calculate total price (assuming the products have a 'price' attribute)
        $total = $cart->products->sum(function ($product) {
            return $product->pivot->quantity * $product->price;
        });

        return view('cart.cart', compact('cart', 'total'));
    }

    public function removeFromCart($productId)
    {
        // Remove the product from the cart
        $cart = Cart::where('user_id', 2)->first();

        if ($cart) {
            $cart->products()->detach($productId);
        }

        // Redirect back to the cart page after removal
        return redirect()->route('cart.cart')->with('success', 'Product removed from cart');
    }
}
