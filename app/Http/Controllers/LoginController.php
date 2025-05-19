<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    public function redirectToProvider(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            $user = User::findOrCreateFromSocialite($socialUser,$provider);

            if ($user->status_id == 2) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa. Lý do: ' . ($user->ban_reason ?? 'Không có lý do cụ thể.'),
                ]);
            }

            Auth::login($user, true);

            return redirect()->route('products.home');
        } catch (Exception $e) {
            Log::error("Lỗi đăng nhập với {$provider}: " . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Đăng nhập thất bại. Vui lòng thử lại.']);
        }
    }



    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google'])) {
            throw new \InvalidArgumentException("Nhà cung cấp {$provider} không được hỗ trợ.");
        }
    }
}

