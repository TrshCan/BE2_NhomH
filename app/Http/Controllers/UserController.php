<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('clients.pages.setting', ['user' => $user]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('clients.pages.setting', compact('user'));
    }


    public function updateProfile(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn phải đăng nhập để thực hiện thao tác này.');
    }

    $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|unique:users,phone,' . $user->id . '|min:10',
        'address' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:6|confirmed',
    ];

    $request->validate($rules);

    // Cập nhật thông tin
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->address = $request->address;


    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Lưu lại thay đổi
    try {
        $user->save();
    } catch (\Exception $e) {
        Log::error('Error saving user data: ' . $e->getMessage());
        return redirect()->route('showUser',['id'=>$user->id])->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
    }


    return redirect()->route('showUser',['id'=>$user->id])->with('success', 'Cập nhật thông tin thành công.');
}





}

