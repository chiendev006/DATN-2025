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
            if (Auth::guard('staff')->user()->role == 0 || Auth::guard('staff')->user()->role == 21) {
                return redirect()->route('staff.index');
            }else{
                return redirect()->route('danhmuc1.index');
            }
        }else{
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
