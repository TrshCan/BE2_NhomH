<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CrudUserController extends Controller
{
    
    public function login()
    {
        return view('login');
    }

    public function authUser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (Auth::attempt($credentials)) {
                return redirect()->intended('home')->with('success', 'Đăng nhập thành công.');
            }

            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);
        } catch (ValidationException $e) {
            return redirect('login')->withErrors($e->errors())->withInput();
        }
    }

    public function createUser()
    {
        return view('register');
    }

    public function postUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'unique:users,phone|min:10',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone'=>$request->phone,
                'password' => Hash::make($request->password),
            ]);

            return redirect('login')->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
        } catch (\Exception $e) {
            return redirect('register')->withErrors(['email' => 'Lỗi đăng ký. Vui lòng thử lại.'])->withInput();
        }
    }

    public function readUser(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect('list')->with('error', 'Người dùng không tồn tại.');
        }

        return view('crud_user.read', ['messi' => $user]);
    }

    public function deleteUser(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect('list')->with('error', 'Người dùng không tồn tại.');
        }

        $user->delete();

        return redirect('list')->with('success', 'Xóa người dùng thành công.');
    }

    public function updateUser(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect('list')->with('error', 'Người dùng không tồn tại.');
        }

        return view('crud_user.update', ['user' => $user]);
    }
 
    public function postUpdateUser(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'required|min:6',
        ]);

        try {
            $user = User::find($request->id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect('list')->with('success', 'Cập nhật người dùng thành công.');
        } catch (\Exception $e) {
            return redirect('update/' . $request->id)->withErrors(['email' => 'Lỗi cập nhật. Vui lòng thử lại.'])->withInput();
        }
    }

    public function listUser()
    {
        if (Auth::check()) {
            $users = User::paginate(10);
            return view('crud_user.list', ['users' => $users]);
        }

        return redirect('login')->with('error', 'Bạn cần đăng nhập để truy cập.');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('login')->with('success', 'Đăng xuất thành công.');
    }
}

