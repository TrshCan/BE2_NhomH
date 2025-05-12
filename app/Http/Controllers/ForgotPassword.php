<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPassword extends Controller
{
    //
    public function forgotPasswordForm(){
        return view('forgot_password');
    }

    public function forgotPasswordFormPost(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
    
        $token = Str::random(64);
    
        // Lưu token vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        Mail::send('emails.forgotpassword', ['token' => $token], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Đặt lại mật khẩu');
        });
    
        return back()->with('success', 'Chúng tôi đã gửi email đặt lại mật khẩu!');
    }

    public function showForm($token){
        return view('reset_password',compact('token'));
    }
    public function resetPassword(Request $request){
        $request ->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email không tồn tại']);
        }

        $first = DB::table('password_reset_tokens')->where('email', $request->email)
        ->where('token',$request->token)
        ->first();
        if (!$first) {
            return redirect()->back()->withErrors(['token' => 'Mã token không đúng']);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return redirect('/login')->with('success', 'Đặt lại mật khẩu thành công!');
    }
}
    