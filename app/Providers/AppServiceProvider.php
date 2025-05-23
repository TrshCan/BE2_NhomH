<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

   

    public function boot()
    {
        View::composer('*', function ($view) {
            $count = 0;

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $count = CartProduct::where('cart_id', $cart->cart_id)->sum('quantity');
                }
            }

            $view->with('cartItemCount', $count);
        });

    }
}
