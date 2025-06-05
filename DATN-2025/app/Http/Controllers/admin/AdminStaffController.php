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
            'salary_per_day' => 'required|numeric|min:0',
        ], [
            'email.unique' => 'Email này đã tồn tại trong hệ thống!',
            'password.confirmed' => 'Mật khẩu không khớp!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
            'salary_per_day.required' => 'Lương hàng ngày là bắt buộc!',
            'salary_per_day.numeric' => 'Lương hàng ngày phải là số!',
            'salary_per_day.min' => 'Lương hàng ngày phải lớn hơn 0!',
            'name.required' => 'Tên nhân viên là bắt buộc!',
        ]);
        $salaryPerDay = str_replace(',', '', $request->salary_per_day);
        $salaryPerDay = str_replace('.', '', $request->salary_per_day); // loại bỏ dấu phẩy nếu có
        $salaryPerDay = floatval($salaryPerDay);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'salary_per_day' => $salaryPerDay,
        ]);
        return redirect()->route('admin.staff.index')->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Cập nhật thông tin nhân viên.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'salary_per_day' => 'required|numeric|min:0',
        ], [
            'email.unique' => 'Email này đã tồn tại trong hệ thống!',
            'password.confirmed' => 'Mật khẩu không khớp!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
            'salary_per_day.required' => 'Lương hàng ngày là bắt buộc!',
            'salary_per_day.numeric' => 'Lương hàng ngày phải là số!',
            'salary_per_day.min' => 'Lương hàng ngày phải lớn hơn 0!',
            'name.required' => 'Tên nhân viên là bắt buộc!',
        ]);

        // Find the user first
        $user = User::findOrFail($id); // Ensures user exists, or throws a 404

        // Prepare data for updating
        $salaryPerDay = str_replace(',', '', $request->salary_per_day);
        $salaryPerDay = str_replace('.', '', $request->salary_per_day); // loại bỏ dấu phẩy nếu có
        $salaryPerDay = floatval($salaryPerDay);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'salary_per_day' => $salaryPerDay,
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
