<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class ForgotPassword extends Controller
{
    // Hiển thị form quên mật khẩu
    public function forgotPasswordForm()
    {
        return view('forgot_password');
    }

    // Gửi email đặt lại mật khẩu
    public function forgotPasswordFormPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email phải có định dạng hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        $plainToken = Str::random(64); // Token gửi qua email
        $hashedToken = Hash::make($plainToken); // Token lưu vào DB

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $hashedToken,
                'created_at' => now()
            ]
        );

        Mail::send('emails.forgotpassword', ['token' => $plainToken, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Đặt lại mật khẩu');
        });

        return back()->with('success', 'Chúng tôi đã gửi email đặt lại mật khẩu!');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showForm($token)
    {
        return view('reset_password', compact('token'));
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'
        ], [
            'token.required' => 'Token là bắt buộc',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['token' => 'Token không hợp lệ']);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['token' => 'Token đã hết hạn']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Đặt lại mật khẩu thành công!');
    }
}
