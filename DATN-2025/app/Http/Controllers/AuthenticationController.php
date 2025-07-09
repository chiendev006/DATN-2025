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
            'email.required' => 'Email không được để trống',
            'password.required' => 'Mật khẩu không được để trôống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',
        ]);

        if (Auth::guard('web')->attempt($data)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Ghi log đăng nhập client
            \App\Models\historylog::create([
                'user_id' => $user->id,
                'role' => $user->role,
                'content' => '<span style="color: green;">Đăng nhập vào hệ thống, trang Cửa hàng</span>',
            ]);

            if ($user->role === 1 || $user->role === '1') {
                Auth::guard('admin')->login($user);
                Auth::guard('staff')->login($user);
            } else {
                if (Auth::guard('admin')->check()) {
                    Auth::guard('admin')->logout();
                }
                if (Auth::guard('staff')->check()) {
                    Auth::guard('staff')->logout();
                }
            }

            // Nếu là yêu cầu AJAX, trả về token
            if ($request->expectsJson()) {
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user,
                    'redirect_url' => '/'
                ]);
            }

            return redirect()->intended('/');
        } else {
            // Nếu là yêu cầu AJAX, trả về lỗi
             if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Email hoặc mật khẩu không chính xác'
                ], 401);
            }
            return redirect()->back()->with([
                'message' => 'Email hoặc mật khẩu không chính xác'
            ]);
        }
    }
    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\historylog::create([
                'user_id' => $user->id,
                'role' => $user->role,
                'content' => '<span style="color: red;">Đăng xuất khỏi hệ thống, trang Cửa hàng</span>',
            ]);
        }
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
        $hasPhone = User::where('phone', $request->phone)->exists();

        if ($hasEmail) {
            return redirect()->back()->with([
                'message' => 'Email đã tồn tại'
            ]);
        }

        if ($hasPhone){
            return redirect()->back()->with([
                'message' => 'Số điện thoại đã tồn tại'
            ]);
        } else{
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'phone' => 'required|numeric|min:10|unique:users',
            ], [
                'name.required' => 'Tên không được để trống',
                'email.required' => 'Email không được để trống',
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
                'phone.required' => 'Số điện thoại không được để trống',
                'phone.phone' =>  'Số điện thoại không đúng định dạng',
                'phone.unique' => 'Số điện thoại đã tồn tại'
            ]);
        }
        $data['password'] = Hash::make($data['password']);
        $data['image'] = 'default.jpg';
        User::create($data);
        return redirect()->route('login')->with([
            'success' => 'Đăng kí thành công'
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
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không có trong hệ thống'
        ]);
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->back()->with([
                'message' => 'Vui lòng check email để tạo mới mật khẩu'
            ]);
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Gửi link reset thất bại'
            ]);
        }
    }
}
