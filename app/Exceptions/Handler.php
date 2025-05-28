<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = ['password', 'password_confirmation'];

    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        // Custom response for rate limit errors
        if ($exception instanceof ThrottleRequestsException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn thao tác quá nhanh. Vui lòng thử lại sau vài giây.',
                ], 429);
            }

            return response()->view('errors.too-many-requests', [], 429);
        }

        return parent::render($request, $exception);
    }
}
