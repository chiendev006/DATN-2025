<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthenticationController extends Controller
{
    public function login()
    {
        // Check if user is already logged in
        if (Auth::check()) {
            return redirect('/')->with('message', 'Bạn đã đăng nhập. Vui lòng đăng xuất trước khi đăng nhập tài khoản khác.');
        }
        return view('client.login2');
    }
    public function postLogin(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email khong duoc de trong',
            'password.required' => 'Mat khau khong duoc de trong',
            'password.min' => 'Mat khau phai it nhat 6 ki tu',
        ]);

        if (Auth::guard('web')->attempt($data)) {
            $user = Auth::user();

            // Only if user is an admin (role must be exactly 1), authenticate admin and staff guards
            if ($user->role === 1 || $user->role === '1') {
                Auth::guard('admin')->login($user);
                Auth::guard('staff')->login($user);
            } else {
                // Make sure non-admin users are NOT logged into admin or staff guards
                if (Auth::guard('admin')->check()) {
                    Auth::guard('admin')->logout();
                }
                if (Auth::guard('staff')->check()) {
                    Auth::guard('staff')->logout();
                }
            }

            return redirect()->intended('/');
        } else {
            return redirect()->back()->with([
                'message' => 'Email hoac mat khau khong chinh xac'
            ]);
        }
    }
    public function logout()
    {
        // Logout from all guards
        Auth::guard('admin')->logout();
        Auth::guard('staff')->logout();
        Auth::logout(); // web guard

        return redirect('/');
    }
    public function register()
    {
        return view('client.register2');
    }
    public function postRegister(Request $request)
    {
        $hasEmail = User::whereEmail($request->email)->exists();

        if ($hasEmail) {
            return redirect()->back()->with([
                'message' => 'Email da ton tai'
            ]);
        }else{
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'phone' => 'required|numeric|min:10',
            ], [
                'name.required' => 'Ten khong duoc de trong',
                'email.required' => 'Email khong duoc de trong',
                'password.required' => 'Mat khau khong duoc de trong',
                'password.min' => 'Mat khau phai it nhat 6 ki tu',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
                'phone.required' => 'So dien thoai khong duoc de trong',
                'phone.phone' =>  'So dien thoai khong dung dinh dang'
            ]);
        }
        $data['password'] = Hash::make($data['password']);
        $data['image'] = 'default.jpg';
        User::create($data);
        return redirect()->route('login')->with([
            'success' => 'Dang ki thanh cong'
        ]);
    }
    public function forgotPassword()
    {
        return view('client.forgot-pass');
    }
    public function sendResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email khong duoc de trong',
            'email.email' => 'Email khong dung dinh dang',
            'email.exists' => 'Email khong co trong he thong'
        ]);
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->back()->with([
                'message' => 'Check email đi bạn hẹ hẹ hẹ'
            ]);
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Gửi link reset thất bại rồi bạn ơi'
            ]);
        }
    }
}
