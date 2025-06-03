<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('staff')->check()) {
            if (Auth::guard('staff')->user()->role == 0 || Auth::guard('staff')->user()->role == 21) {
                return $next($request);
            }else{
                return redirect()->route('staff.login');
            }
        }else{
            return redirect()->route('staff.login')->with([
                'message' => 'Bạn phải đăng nhập!'
            ]);
        }
    }
}
