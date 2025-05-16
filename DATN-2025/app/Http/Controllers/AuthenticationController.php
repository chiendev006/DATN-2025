<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function showLoginForm()
    {
        return view('client.login');
    }
    public function postLogin(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($data)) {
            return redirect()->route('danhmuc1.index');
        }else{
            return redirect()->back()->with([
                'message' => 'Email hoặc mật khẩu không đúng!'
            ]);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('danhmuc1.index');
    }
    public function showRegisterForm()
    {
        return view('client.register');
    }
    public function postRegister(Request $request)
    {
       $hasEmail = User::whereEmail($request->email)->exists();
       if($hasEmail){
           return redirect()->back()->with([
               'message' => 'Email da ton tai!'
           ]);
       }else{
           User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => bcrypt($request->password),
           ]);
           return redirect()->route('login')->with([
               'message' => 'dang ki thanh cong!'
           ]);
       }
    }
}
