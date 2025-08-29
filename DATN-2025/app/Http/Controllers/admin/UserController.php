<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\historylog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // per_page hợp lệ: 10/25/50/100
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        // Chỉ lấy user KHÔNG phải nhân viên (21,22)
        $users = User::query()
            ->whereNotIn('role', [21, 22])
            ->orderBy('id')
            ->paginate($perPage)
            ->appends($request->except('page'));

        return view('admin.user.index', compact('users'));
    }

    /**
     * Xóa nhân viên.
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // xóa mềm (chỉ set deleted_at)

        return redirect()->route('user.index')->with('success', 'Đã xóa mềm tài khoản!');
    }
}
