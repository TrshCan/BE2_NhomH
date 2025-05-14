<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CrudUserController extends Controller
{

    public function login()
    {
        return view('login');
    }
    public function adminpanel()
    {
        return view('admin.admin');
    }

    public function authUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::with('status')->where('email', $request->email)->first();

        Log::info('User login attempt', [
            'email' => $request->email,
            'user_exists' => !empty($user),
            'status_id' => $user ? $user->status_id : null,
            'status_name' => $user && $user->status ? $user->status->name : 'No status',
            'ban_reason' => $user ? ($user->ban_reason ?? 'No ban reason') : 'No user',
        ]);

        try {
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => 'Email hoặc mật khẩu không đúng.',
                ]);
            }

            // Kiểm tra trạng thái tài khoản
            if ($user->status && $user->status->name === 'Đã khóa') {
                Log::info('Blocked login attempt due to locked status', [
                    'email' => $request->email,
                    'status_id' => $user->status_id,
                    'status_name' => $user->status->name,
                    'ban_reason' => $user->ban_reason,
                ]);
                throw ValidationException::withMessages([
                    'email' => 'Tài khoản của bạn đã bị khóa. Lý do: ' . ($user->ban_reason ?? 'Không có lý do cụ thể.'),
                ]);
            }

            if (Auth::attempt($credentials)) {
                Log::info('Login successful', ['email' => $request->email]);
                return redirect()->intended('/')->with('success', 'Đăng nhập thành công.');
            }

            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);
        } catch (ValidationException $e) {
            Log::info('Validation exception caught', [
                'email' => $request->email,
                'errors' => $e->errors(),
            ]);
            return redirect('login')->withErrors($e->errors())->withInput();
        }
    }

    public function createUser()
    {
        return view('register');
    }
    // public function storeUser(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email|max:255',
    //         'password' => 'required|string|min:8|confirmed',  
    //         'phone' => 'required|numeric|digits:10',           
    //         'address' => 'required|string|max:255',
    //     ]);

    //     // Nếu có lỗi validation
    //     if ($validator->fails()) {
    //         return redirect()->back()
    //                          ->withErrors($validator)
    //                          ->withInput(); // Trả về lại form với dữ liệu đã nhập
    //     }


    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //     ]);


    //     Auth::loginUsingId(User::latest()->first()->id);

    //     return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    // }

    public function postUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|regex:/^[\S]+$/|',
            'phone' => 'unique:users,phone|min:10',
            'address' => 'required',
        ], [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được đăng ký.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.regex' => 'Mật khẩu không được chứa khoảng trắng.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
            'address.required' => 'Địa chỉ là bắt buộc.',
        ]);


        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            return redirect('login')->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
        } catch (\Exception $e) {
            return redirect('register')->withErrors(['email' => 'Lỗi đăng ký. Vui lòng thử lại.'])->withInput();
        }
    }

    public function showUser(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect('list')->with('error', 'Người dùng không tồn tại.');
        }

        return view('admin.show', compact('user'));
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

    public function updateUser($id)
    {
        $user = User::findOrFail($id);
        $statuses = Status::all();
        return view('admin.update', compact('user', 'statuses'));
    }

    public function postUpdateUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);

            // Xây dựng rules xác thực tùy theo loại người dùng
            $rules = [
                'id' => 'required|exists:users,id',
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $request->id,
                'phone' => 'nullable|unique:users,phone,' . $request->id . '|min:10',
                'status_id' => 'required|exists:statuses,id',
                'ban_reason' => 'nullable|string',
            ];

            // Nếu không phải tài khoản Google thì bắt buộc có địa chỉ
            if (!$user->google_id) {
                $rules['address'] = 'required';
            } else {
                $rules['address'] = 'nullable|string';
            }

            $request->validate($rules);

            // Cập nhật thông tin
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->status_id = $request->status_id;

            if ($request->status_id == 2) {
                if (empty($request->ban_reason)) {
                    return redirect()->route('user.update', $request->id)
                        ->withErrors(['ban_reason' => 'Vui lòng nhập lý do khóa tài khoản.'])
                        ->withInput();
                }
                $user->ban_reason = $request->ban_reason;
            } else {
                $user->ban_reason = null;
            }

            $user->save();
            Log::info('User update', ['user' => $user, 'request' => $request->all()]);

            return redirect()->route('user.list')->with('success', 'Cập nhật người dùng thành công.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), ['user_id' => $request->id]);
            return redirect()->route('user.update', $request->id)
                ->withErrors(['error' => 'Lỗi cập nhật. Vui lòng thử lại.'])
                ->withInput();
        }
    }



    public function listUser(Request $request)
    {
        // Nếu chưa đăng nhập
        if (!Auth::check()) {
            return redirect('login')->with('error_admin', 'Bạn cần đăng nhập và có quyền admin để truy cập.');
        }

        // Nếu đã đăng nhập nhưng không phải admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('login')->with('error_admin', 'Bạn không có quyền truy cập trang quản trị. Đã đăng xuất.');
        }

        // Nếu là admin
        $query = User::with('status');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        $users = $query->paginate(10);
        return view('admins.users.list', ['users' => $users]);
    }



    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('/')->with('success', 'Đăng xuất thành công.');
    }
}
