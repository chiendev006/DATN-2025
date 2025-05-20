<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Psy\Util\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('client.reset-pass', ['token' => $token]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',
            'password.confirmed' => 'Mật khẩu xác nhận không đúng',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with([
                'message' => 'Đổi mật khẩu thành công'
            ]);
        } else {
            return back()->withErrors(['email' => [__($status)]]);
        }
    }
}
