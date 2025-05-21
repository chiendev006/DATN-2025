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
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->role == '1') {
                return $next($request);
            }else{
                return redirect()->route('admin.login')->with([
                    'message' => 'Bạn không phải admin',
                ]);
            }
        }else{
            return redirect()->route('admin.login')->with([
                'message' => 'Bạn phải đăng nhập trước'
            ]);
        }
    }
}
