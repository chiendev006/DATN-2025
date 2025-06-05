<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckValidId
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu có tham số id trên route
        $id = $request->route('id');
        if ($id !== null && (!is_numeric($id) || $id <= 0)) {
            return redirect()->back()->with('error', 'ID không hợp lệ hoặc không tồn tại!');
        }

        // Kiểm tra nếu là batch (nhiều id)
        $ids = $request->input('ids');
        if ($ids !== null) {
            if (!is_array($ids) || count($ids) == 0) {
                return redirect()->back()->with('error', 'Bạn phải chọn ít nhất một mục hợp lệ!');
            }
            foreach ($ids as $itemId) {
                if (!is_numeric($itemId) || $itemId <= 0) {
                    return redirect()->back()->with('error', 'Có ID không hợp lệ trong danh sách!');
                }
            }
        }

        return $next($request);
    }
}