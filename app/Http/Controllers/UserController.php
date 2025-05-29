<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function showUser($id)
{
    $user = User::findOrFail($id);
    $orders = Order::where('user_id', $id)
        ->orderBy('order_date', 'desc')
        ->paginate(5); // Paginate 5 orders per page

    return view('clients.pages.setting', ['user' => $user, 'orders' => $orders]);
}


    public function passwordUser()
    {
        return view('clients.pages.change-password');
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('clients.pages.setting', compact('user'));
    }


    public function updateProfile(Request $request)
{
    // Lấy người dùng đã đăng nhập
    $user = Auth::user();
    
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn phải đăng nhập để thực hiện thao tác này.');
    }

    $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|unique:users,phone,' . $user->id . '|min:10',
        'address' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:6|confirmed', // password_confirmation
    ];

    $request->validate($rules);

    // Cập nhật thông tin
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->address = $request->address;

    // Chỉ cập nhật mật khẩu nếu người dùng nhập
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

