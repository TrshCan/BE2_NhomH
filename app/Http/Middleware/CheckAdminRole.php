<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if(!Auth::check()){
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để truy cập.');
        }
        if(Auth::user()->role!=='admin'){
            Auth::logout();
            return redirect('/login')->with('error', 'Bạn không có quyền quản trị để truy cập trang này.');
        }
        return $next($request);
    }
}
