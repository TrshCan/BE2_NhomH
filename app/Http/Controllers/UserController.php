<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function showUser()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
        }

        $orders = Order::where('user_id', $user->id)
            ->orderBy('order_date', 'desc')
            ->paginate(5);

        return view('clients.pages.setting', ['orders' => $orders, 'user' => $user]);
    }



    public function passwordUser()
    {
        return view('clients.pages.change-password');
    }

    public function editProfile()
    {
        $authUser = Auth::user();

        return view('clients.pages.setting', ['user' => $authUser]);
    }




    public function updateProfile(Request $request)
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return redirect()->route('login')->with('error', 'Bạn phải đăng nhập.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|unique:users,phone,' . $authUser->id,
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $authUser->fill($request->only(['name', 'phone', 'address']));
        if ($request->filled('password')) {
            $authUser->password = bcrypt($request->password);
        }

        $authUser->save();

        return redirect()->route('showUser', ['id' => $authUser->id])->with('success', 'Cập nhật thành công.');
    }





}

