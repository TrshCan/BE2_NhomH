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
use Illuminate\Pagination\Paginator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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

        Paginator::useBootstrapFive();
    }
    protected function configureRateLimiting()
    {
        RateLimiter::for('add-to-cart', function (Request $request) {
            return Limit::perMinutes(2, 1)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('add-order', function (Request $request) {
            return Limit::perMinutes(1, 5)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinutes(5, 2)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('add-coupon', function (Request $request) {
            return Limit::perMinutes(1, 3)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
