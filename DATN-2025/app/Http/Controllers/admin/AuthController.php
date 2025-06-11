<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }
    public function postLogin(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('admin')->attempt($data)) {
            if (Auth::guard('admin')->user()->role == '1') {
                // Get the authenticated admin user
                $adminUser = Auth::guard('admin')->user();

                // Manually login for staff and web guards using the same user
                Auth::guard('staff')->login($adminUser);
                Auth::login($adminUser); // Default web guard

                return redirect()->route('home.index');
            } else {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with([
                    'message' => 'Bạn không phải admin'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Email hoặc mật khẩu không chính xác'
            ]);
        }
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        Auth::guard('staff')->logout();
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
