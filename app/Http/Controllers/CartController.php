<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        // $userId = \Illuminate\Support\Facades\Auth::id();
        $userId = 1;
        $product = Product::findOrFail($id);

        // Get or create the user's cart
        $cart = \App\Models\Cart::firstOrCreate(['user_id' => $userId]);

        // Add the product to the cart using the relationship
        $cart->products()->attach($product->id, [
            'quantity' => \Illuminate\Support\Facades\DB::raw('quantity + 1'),
        ]);

        // Get the updated quantity
        $quantity = $cart->products()->where('products.id', $product->id)->first()->pivot->quantity;

        return response()->json([
            'message' => 'Product added to cart!',
            'product' => $product->name,
            'quantity' => $quantity,
        ]);
    }

    public function index(Request $request)
    {
        // Fetch cart data from the session.  This is simpler, but doesn't persist across logins.
        $cart = session('cart', []);

        // Get product details from the database based on the IDs in the cart.
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Calculate the total price and quantity of items in the cart.
        $total = 0;
        $amount = 0;

        foreach ($cart as $id => $item) {
            if (isset($products[$id])) {
                $total += $products[$id]->price * $item['quantity'];
                $amount += $item['quantity'];
            }
        }

        $isLoggedIn = false; //  We are NOT checking for login

        // Pass the data to the view.
        return view('cart.cart', compact('cart', 'products', 'total', 'amount', 'isLoggedIn'));
    }

    // public function index(Request $request)
    // {
    //     // Check if the user is logged in
    //     if (!\Illuminate\Support\Facades\Auth::check()) { // Corrected line
    //         // Redirect to the login page if the user is not logged in
    //         return redirect()->route('products.home'); //  Make sure 'login' is your login route name
    //     }

    //     // Get the logged-in user's ID
    //     $userId = \Illuminate\Support\Facades\Auth::id(); // Corrected line

    //     // Fetch the user's cart.  We'll eager load the items and products.
    //     $cart = \App\Models\Cart::with('cartItems.product')  // Assumes you have a Cart model.
    //         ->where('user_id', $userId)
    //         ->first();

    //     // Initialize variables for total and amount
    //     $total = 0;
    //     $amount = 0;
    //     $products = []; // Initialize as empty array.

    //     // If a cart exists, process its items.
    //     if ($cart) {
    //         //  $cart->cartItems is a collection of CartItem models.
    //         foreach ($cart->cartItems as $cartItem) {
    //             $product = $cartItem->product; // Access the Product model.
    //             $quantity = $cartItem->quantity;

    //             $total += $product->price * $quantity;
    //             $amount += $quantity;
    //             $products[$product->id] = $product; // Make $products available for the view, if needed
    //         }
    //     }


    //     $isLoggedIn = true; // User is logged in, so this is always true here.

    //     // Pass the data to the view.  Adjust view name as needed.
    //     return view('cart.cart', compact('cart', 'products', 'total', 'amount', 'isLoggedIn'));
    // }
}
