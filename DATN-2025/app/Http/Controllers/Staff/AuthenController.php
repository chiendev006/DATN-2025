<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenController extends Controller
{
    public function login()
    {
        return view('staff.login');
    }
    public function postLogin(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::guard('staff')->attempt($data)) {
            $role = Auth::guard('staff')->user()->role;

            // Admin role (1) can access any route
            if ($role == 1) {
                // Redirect to staff dashboard by default
                return redirect()->route('staff.index');
            }
            // Staff role (21) goes to staff dashboard
            else if ($role == 21) {
                return redirect()->route('staff.index');
            }
            // Bartender role (22) goes to bartender dashboard
            else if ($role == 22) {
                return redirect()->route('bartender.index');
            }
            // Other roles redirect to login with message
            else {
                Auth::guard('staff')->logout();
                return redirect()->route('staff.login')->with([
                    'message' => 'Tài khoản của bạn không có quyền truy cập.',
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Tài khoản hoặc mật khẩu không đúng',
            ]);
        }
    }
    public function logout()
    {
        Auth::guard('staff')->logout();
        return redirect()->route('staff.login');
    }
}
