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
        // First, check if the user is logged in with the admin guard
        if (Auth::guard('admin')->check()) {
            // Strict check for admin role
            if (Auth::guard('admin')->user()->role === 1 || Auth::guard('admin')->user()->role === '1') {
                // User is admin, ensure they're logged in to all guards
                $adminUser = Auth::guard('admin')->user();

                if (!Auth::guard('staff')->check()) {
                    Auth::guard('staff')->login($adminUser);
                }

                if (!Auth::check()) {
                    Auth::login($adminUser);
                }

                return $next($request);
            } else {
                // Not an admin, logout from admin guard and redirect
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with([
                    'message' => 'Bạn không phải admin',
                ]);
            }
        }

        // If not logged in with admin guard, check web guard
        if (Auth::check()) {
            // Strict check for admin role
            if (Auth::user()->role === 1 || Auth::user()->role === '1') {
                // User is admin, log them in to all guards
                $user = Auth::user();

                if (!Auth::guard('admin')->check()) {
                    Auth::guard('admin')->login($user);
                }

                if (!Auth::guard('staff')->check()) {
                    Auth::guard('staff')->login($user);
                }

                return $next($request);
            } else {
                // Not an admin, ensure they're logged out of admin guard
                if (Auth::guard('admin')->check()) {
                    Auth::guard('admin')->logout();
                }

                // Redirect non-admin users to home page with message
                return redirect('/')->with([
                    'message' => 'Bạn không có quyền truy cập trang quản trị',
                ]);
            }
        }

        // Not logged in at all, redirect to admin login
        return redirect()->route('admin.login')->with([
            'message' => 'Bạn phải đăng nhập trước'
        ]);
    }
}
