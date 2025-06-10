<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra nếu người dùng đã đăng nhập ở guard admin
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->role == '1') {
                return $next($request);
            } else {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with([
                    'message' => 'Bạn không phải admin',
                ]);
            }
        }

        // Kiểm tra nếu người dùng đã đăng nhập ở guard web
        if (Auth::check()) {
            if (Auth::user()->role == '1') {
                // Đăng nhập lại vào guard admin
                Auth::guard('admin')->login(Auth::user());
                return $next($request);
            } else {
                // Logout khỏi admin guard nếu đã đăng nhập
                if (Auth::guard('admin')->check()) {
                    Auth::guard('admin')->logout();
                }
                return redirect()->route('admin.login')->with([
                    'message' => 'Bạn không phải admin',
                ]);
            }
        }

        // Nếu chưa đăng nhập
        return redirect()->route('admin.login')->with([
            'message' => 'Bạn phải đăng nhập trước'
        ]);
    }
}
