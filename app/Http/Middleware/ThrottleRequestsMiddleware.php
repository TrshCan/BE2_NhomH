<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThrottleRequestsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = 'throttle_' . ($request->user()?->id ?? $request->ip());
        $lastRequestTime = Cache::get($key, 0); // Default to 0 if not found

        $currentTime = microtime(true); // Get current time in milliseconds

        // Check if last request was less than 1 second ago
        if (($currentTime - $lastRequestTime) < 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau!',
                ], 429);
            } else {
                return redirect()->back()->with('error', 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau!');
            }
        }

        // Update cache with new request timestamp
        Cache::put($key, $currentTime, 10);

        return $next($request);
    }
}
