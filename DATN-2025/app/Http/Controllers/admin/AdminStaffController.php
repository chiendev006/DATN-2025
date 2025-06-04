<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    public function login()
    {
        return view('staff.login');
    }

    /**
     * Hiển thị danh sách nhân viên (user có role staff).
     */
    public function staffIndex(Request $request)
    {
        $per_page = $request->input('per_page', 10); // Default to 10 items per page
        $staffs = User::where('role', '21')
                     ->orWhere('role', '22')
                     ->paginate($per_page);
        return view('admin.staff.index', compact('staffs'));
    }


    /**
     * Lưu nhân viên mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',

        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('admin.staff.index')->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Cập nhật thông tin nhân viên.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255', // Added max length for good practice
        'email' => 'required|email|unique:users,email,' . $id, // Corrected unique rule
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    // Find the user first
    $user = User::findOrFail($id); // Ensures user exists, or throws a 404

    // Prepare data for updating
    $updateData = [
        'name' => $request->name,
        'email' => $request->email,
    ];

    // If a new password is provided, hash and add it to the update data
    if ($request->filled('password')) {
        $updateData['password'] = bcrypt($request->password);
    }

    // Update the user
    $user->update($updateData);

    return redirect()->route('admin.staff.index')->with('success', 'Cập nhật nhân viên thành công!');
}

    /**
     * Xóa nhân viên.
     */
    public function delete($id)
    {
        $staff = User::where('role', '21')->orWhere('role', '22')->findOrFail($id);
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Đã xóa nhân viên!');
    }
}
