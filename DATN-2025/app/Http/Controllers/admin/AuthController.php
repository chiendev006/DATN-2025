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
            $user = Auth::guard('admin')->user();
            if ($user->role == '1') {
                Auth::guard('staff')->login($user);
                Auth::login($user); // Default web guard

                if ($request->expectsJson()) {
                    $token = $user->createToken('admin-auth-token')->plainTextToken;
                    return response()->json([
                        'message' => 'Admin login successful',
                        'token' => $token,
                        'user' => $user,
                        'redirect_url' => route('home.index')
                    ]);
                }
                return redirect()->route('home.index');
            } else {
                Auth::guard('admin')->logout();
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Bạn không phải admin'], 403);
                }
                return redirect()->route('admin.login')->with(['message' => 'Bạn không phải admin']);
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email hoặc mật khẩu không chính xác'], 401);
            }
            return redirect()->back()->with(['message' => 'Email hoặc mật khẩu không chính xác']);
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
