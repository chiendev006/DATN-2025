<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\historylog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStaffController extends Controller
{
    public function login()
    {
        return view('staff.login');
    }

    /**
     */
    public function staffIndex(Request $request)
    {
        $per_page = $request->input('per_page', 10);
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
            'role' => 'required|in:21,22',

        ], [
            'email.unique' => 'Email này đã tồn tại trong hệ thống!',
            'password.confirmed' => 'Mật khẩu không khớp!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
            'salary_per_day.required' => 'Lương hàng ngày là bắt buộc!',
            'salary_per_day.numeric' => 'Lương hàng ngày phải là số!',
            'salary_per_day.min' => 'Lương hàng ngày phải lớn hơn 0!',
            'name.required' => 'Tên nhân viên là bắt buộc!',
            'role.required' => 'Chức vụ là bắt buộc!',
            'role.in' => 'Chức vụ không hợp lệ!',

        ]);
        $salaryPerDay = str_replace(',', '', $request->salary_per_day);
        $salaryPerDay = str_replace('.', '', $request->salary_per_day); // loại bỏ dấu phẩy nếu có
        $salaryPerDay = floatval($salaryPerDay);

        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'salary_per_day' => $salaryPerDay,
            'phone' => $request->phone,
            'image' => $request->image,
        ]);
          $content1='';
        $content2='';
        $title='<strong>Thêm nhân viên:</strong> <br>';
            $content1=" *<span style='color: red;'>Tên:</span> <b>$user->name</b> <br>";
            $content2=" *<span style='color: red;'>Email:</span> <b>$user->email</b>  <br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
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
            'role' => 'required|in:21,22',
            'phone' => 'string|max:111',

        ], [
            'email.unique' => 'Email này đã tồn tại trong hệ thống!',
            'password.confirmed' => 'Mật khẩu không khớp!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
            'salary_per_day.required' => 'Lương hàng ngày là bắt buộc!',
            'salary_per_day.numeric' => 'Lương hàng ngày phải là số!',
            'salary_per_day.min' => 'Lương hàng ngày phải lớn hơn 0!',
            'name.required' => 'Tên nhân viên là bắt buộc!',
            'role.required' => 'Chức vụ là bắt buộc!',
            'role.in' => 'Chức vụ không hợp lệ!',
            'phone.max' => 'Số điện thoại không hợp lệ!',

        ]);

        // Find the user first
        $user = User::findOrFail($id); // Ensures user exists, or throws a 404

        // Prepare data for updating
        $salaryPerDay = str_replace(',', '', $request->salary_per_day);
        $salaryPerDay = str_replace('.', '', $request->salary_per_day); // loại bỏ dấu phẩy nếu có
        $salaryPerDay = floatval($salaryPerDay);


      if($user->email!=$request->email){
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'salary_per_day' => $salaryPerDay,
            'role' => $request->role,
            'phone' => $request->phone,
        ];
      } else {
        $updateData = [
            'name' => $request->name,
            'salary_per_day' => $salaryPerDay,
            'role' => $request->role,
            'phone' => $request->phone,
        ];
      }



        // Nếu có file upload mới thì xử lý
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/uploads'), $imageName);
            $updateData['image'] = $imageName;
        }

        // If a new password is provided, hash and add it to the update data
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }
        $userid=User::find($id);
        // Update the user
        $user->update($updateData);
        $content1='';
        $content2='';
        $content3='';
        $content4='';
        $content5='';
        $content6='';

        $title="<strong>Sửa thông tin nhân viên: $user->name</strong> <br>";
        if($userid->name!=$user->name){
            $content1=" *<span style='color: red;'>Tên:</span> <b>$userid->name</b><span style='color: blue;'> thành </span><b>$user->name</b> <br>";
        }
        if($userid->phone!=$user->phone){
            $content2=" *<span style='color: red;'>Số điện thoại:</span> <b>$userid->phone</b><span style='color: blue;'> thành </span><b>$user->phone</b> <br>";
        }

          if($userid->email!=$user->email){
            $content3=" *<span style='color: red;'>Email:</span> <b>$userid->email</b><span style='color: blue;'> thành </span><b>$user->email</b> <br>";
        }
        if($userid->role!=$user->role){
          $role_userid = ($userid->role == 21) ? "nhân viên thu ngân" : "nhân viên pha chế";
            $role_user = ($user->role == 21) ? "nhân viên thu ngân" : "nhân viên pha chế";

            $content4 = " *<span style='color: red;'>Chức vụ:</span> <b>$role_userid</b><span style='color: blue;'> thành</span> <b>$role_user</b> <br>";
        }

          if($userid->salary_per_day!=$user->salary_per_day){
            $content5=" *<span style='color: red;'>Lương:</span> <b>$userid->salary_per_day</b><span style='color: blue;'> thành </span><b>$user->salary_per_day</b> <br>";        }
          if ($userid->image!=$user->image) {

            $content6 = "*<span style='color: red;'>Ảnh nhân viên:</span> <img src=\"" . url("/storage/uploads/$user->image") . "\" alt=\"\" width=\"100px\" height=\"100px\">";
        }

      if($content1!=''||$content2!=''||$content3!=''||$content4!=''||$content5!=''||$content6!=''){
        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2.$content3.$content4.$content5.$content6,
        ]);
      }
        return redirect()->route('admin.staff.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Xóa nhân viên.
     */
    public function delete($id)
    {
        $staff = User::where('role', '21')->orWhere('role', '22')->findOrFail($id);
        $username=$staff->name;
        $useremail=$staff->email;
        $staff->delete();
         $content1='';
        $content2='';
        $title='<strong>Xóa nhân viên:</strong> <br>';
            $content1=" *<span style='color: red;'>Tên:</span> <b>$username</b> <br>";
            $content2=" *<span style='color: red;'>Email:</span> <b>$useremail</b><br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
        return redirect()->route('admin.staff.index')->with('success', 'Đã xóa nhân viên!');
    }
}
