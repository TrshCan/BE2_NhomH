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
            $user = $this->findOrCreateUser($socialUser, $provider);
            Auth::login($user, true);
            return redirect()->route('home');
        } catch (Exception $e) {
            Log::error("Lỗi đăng nhập với {$provider}: " . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Đăng nhập thất bại. Vui lòng thử lại.']);
        }
    }

    private function findOrCreateUser($socialUser, string $provider): User
    {
        $user = User::where("{$provider}_id", $socialUser->getId())->first();

        if ($user) {
            return $user;
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update(["{$provider}_id" => $socialUser->getId()]);
            return $user;
        }

        return User::create([
            "{$provider}_id" => $socialUser->getId(),
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'email_verified_at' => now(),
        ]);
    }

    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            throw new \InvalidArgumentException("Nhà cung cấp {$provider} không được hỗ trợ.");
        }
    }
}