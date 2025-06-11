<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Closure;
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
            $staffUser = Auth::guard('staff')->user();
            $role = $staffUser->role;

            // If user is admin (role 1), also authenticate admin and web guards
            if ($role == 1) {
                Auth::guard('admin')->login($staffUser);
                Auth::login($staffUser); // web guard

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
        // Check if the user is an admin before logging out
        $isAdmin = Auth::guard('staff')->check() && Auth::guard('staff')->user()->role == 1;

        // If admin, logout from all guards
        if ($isAdmin) {
            Auth::guard('admin')->logout();
            Auth::guard('staff')->logout();
            Auth::logout(); // web guard
        } else {
            // Just logout from staff guard
            Auth::guard('staff')->logout();
        }

        return redirect()->route('staff.login');
    }
}
