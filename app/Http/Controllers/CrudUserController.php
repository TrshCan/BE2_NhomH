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
use Illuminate\Validation\Rule;

class CrudUserController extends Controller
{

    public function login()
    {
        return view('login');
    }
//    public function adminpanel()
//    {
//        return view('admin.admin');
//    }

    public function authUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

       $user = User::findByEmailWithStatus($request->email);

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
                Log::info('Login successful', [
                    'email' => $request->email,
                    'role' => $user->role,
                ]);

                // Redirect based on user role
                if ($user->role === 'admin') {
                    return redirect()->intended(route('admin.adminPanel'))->with('success', 'Đăng nhập thành công.');
                }

                return redirect()->intended(route('products.home'))->with('success', 'Đăng nhập thành công.');
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
            'name' => [
                'required',
                'min:4',
                'max:30',
                'regex:/^[\pL]+(?: [\pL]+)*$/u'
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'min:6',
                'max:64',
                'regex:/^\S+$/'
            ],
            'phone' => [
                'nullable',
                'numeric',
                'digits_between:10,11',
                'unique:users,phone'
            ],
            'address' => [
                'required',
                'string',
                'max:255'
            ],
        ], [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'name.min' => 'Tên người dùng phải có ít nhất 4 ký tự.',
            'name.max' => 'Tên người dùng không được vượt quá 30 ký tự.',
            'name.regex' => 'Tên chỉ được chứa chữ cái,khoảng trắng (không có 2 khoảng trắng liên tiếp, không bắt đầu/kết thúc bằng khoảng trắng.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.max' => 'Email không được dài quá 100 ký tự.',
            'email.unique' => 'Email này đã được đăng ký.',

            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 64 ký tự.',
            'password.regex' => 'Mật khẩu không được chứa khoảng trắng.',

            'phone.numeric' => 'Số điện thoại chỉ được chứa số.',
            'phone.digits_between' => 'Số điện thoại phải có từ 10 đến 11 chữ số.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký.',

            'address.required' => 'Địa chỉ là bắt buộc.',
            'address.max' => 'Địa chỉ không được dài quá 255 ký tự.',
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
        $user = User::findOrFail($request->id);

        $rules = [
            'id' => 'required|exists:users,id',
            'name' => 'required|min:4|max:30|regex:/^[\pL]+(?: [\pL]+)*$/u',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($request->id),
            ],
            'phone' => [
                'nullable',
                'numeric',
                'digits_between:10,11',
                Rule::unique('users', 'phone')->ignore($request->id),
            ],
            'status_id' => 'required|exists:statuses,id',
            'ban_reason' => 'nullable|string|max:255',
        ];

        if (!$user->google_id) {
            $rules['address'] = 'required|string|max:255';
        } else {
            $rules['address'] = 'nullable|string|max:255';
        }
        if ($request->status_id == 2 && empty($request->ban_reason)) {
            return redirect()->route('admin.updateUser', $request->id)
                ->withErrors(['ban_reason' => 'Vui lòng nhập lý do khóa tài khoản.'])
                ->withInput();
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'name.min' => 'Tên phải có ít nhất 4 ký tự.',
            'name.max' => 'Tên không vượt quá 30 ký tự.',
            'name.regex' => 'Tên chỉ được chứa chữ cái,khoảng trắng (không có 2 khoảng trắng liên tiếp, không bắt đầu/kết thúc bằng khoảng trắng).',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.numeric' => 'Số điện thoại phải là số.',
            'phone.digits_between' => 'Số điện thoại phải từ 10 đến 11 số.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',
            'status_id.required' => 'Trạng thái là bắt buộc.',
            'status_id.exists' => 'Trạng thái không hợp lệ.',
            'address.required' => 'Địa chỉ là bắt buộc.',
            'address.max' => 'Độ dài địa chỉ không được vuợt quá 255 kí tự!',
            'ban_reason.max' => 'Độ dài quy định không được vượt quá 255 kí tự!'
        ]);
        try {

            // Cập nhật dữ liệu
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->status_id = $request->status_id;
            $user->ban_reason = $request->status_id == 2 ? $request->ban_reason : null;

            $user->save();

            Log::info('User updated', ['user_id' => $user->id]);

            return redirect()->route('admin.indexUser')->with('success', 'Cập nhật người dùng thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật user: ' . $e->getMessage(), ['user_id' => $request->id]);

            return redirect()->route('admin.updateUser', $request->id)
                ->withErrors(['error' => 'Lỗi cập nhật. Vui lòng thử lại.'])
                ->withInput();
        }
    }



    public function index(Request $request)
    {
        // Nếu chưa đăng nhập
        if (!Auth::check()) {
            return redirect('login')->with('withoutLogin', 'Bạn cần đăng nhập và có quyền admin để truy cập.');
        }
        $user = Auth::user();
        if (!$user||$user->role !== 'admin') {
            Auth::logout();
            return redirect('login')->with('withoutAdmin', 'Bạn không có quyền truy cập trang quản trị. Đã đăng xuất.');
        }

     $users = User::getUsersWithStatus($request->search);
        return view('admin.list', ['users' => $users]);
    }



    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('/')->with('success', 'Đăng xuất thành công.');
    }
}
