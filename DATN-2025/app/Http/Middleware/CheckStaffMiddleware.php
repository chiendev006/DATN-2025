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
            $role = Auth::guard('staff')->user()->role;
            $path = $request->path();

            // Role 1 (Admin) can access any staff route
            if ($role == 1) {
                return $next($request);
            }
            // Role 21 (Staff) can only access staff routes
            else if ($role == 21) {
                // If trying to access bartender routes, redirect to staff dashboard
                if (str_starts_with($path, 'bartender')) {
                        return redirect()->route('staff.index')->with('message', 'Bạn không có quyền truy cập trang này.');
                }
                return $next($request);
            }
            // Role 22 (Bartender) can only access bartender routes
            else if ($role == 22) {
                // If trying to access staff routes, redirect to bartender dashboard
                if (str_starts_with($path, 'staff') && !str_starts_with($path, 'staff/login') && !str_starts_with($path, 'staff/logout')) {
                    return redirect()->route('bartender.index')->with('message', 'Bạn không có quyền truy cập trang này.');
                }
                return $next($request);
            }
            // Other roles don't have access
            else {
                Auth::guard('staff')->logout();
                return redirect()->route('staff.login')->with('message', 'Tài khoản của bạn không có quyền truy cập.');
            }
        } else {
            return redirect()->route('staff.login')->with([
                'message' => 'Bạn phải đăng nhập!'
            ]);
        }
    }
}
