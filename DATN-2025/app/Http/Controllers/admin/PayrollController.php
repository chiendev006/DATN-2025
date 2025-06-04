<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\User;
use App\Models\PayrollDetail;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $payroll = Payroll::paginate($per_page);
        return view('admin.payroll.index', compact('payroll', 'per_page'));
    }

    public function store(Request $request)
    {
        $month = $request->input('month'); // định dạng: 2024-06
        $payroll = Payroll::create(['month' => $month, 'status' => 0]);

        $users = User::whereIn('role', [21, 22])->get();
        foreach ($users as $user) {
            $attendances = Attendance::where('user_id', $user->id)
                ->whereMonth('date', substr($month, 5, 2))
                ->whereYear('date', substr($month, 0, 4))
                ->pluck('date');

            $workDays = $attendances->map(function($date) {
                return (int)date('j', strtotime($date));
            })->toArray();

            $salary_per_day = $user->salary_per_day ?? 0;
            $total_salary = count($workDays) * $salary_per_day;

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'user_id' => $user->id,
                'total_days' => count($workDays),
                'total_salary' => $total_salary,
                'work_days' => json_encode($workDays),
            ]);
        }
        $payroll->total = $payroll->details()->sum('total_salary');
        $payroll->save();
        return redirect()->route('payroll.index')->with('success', 'Tạo bảng lương thành công!');
    }

    public function show($id)
    {
        $payroll = Payroll::with('details.user')->findOrFail($id);
        $total_pay = $payroll->details->sum('total_salary');
        return view('admin.payroll.show', compact('payroll', 'total_pay'));
    }

    public function toggleWorkDay(Request $request)
    {
        $detail = \App\Models\PayrollDetail::findOrFail($request->detail_id);
        $workDays = $detail->work_days ? array_map('intval', explode(',', $detail->work_days)) : [];
        $day = intval($request->day);

        if (in_array($day, $workDays)) {
            $workDays = array_diff($workDays, [$day]);
            $status = 'removed';
        } else {
            $workDays[] = $day;
            $workDays = array_unique($workDays);
            sort($workDays);
            $status = 'added';
        }

        $detail->work_days = implode(',', $workDays);
        $detail->total_days = count($workDays);

        // Cập nhật lại lương theo salary_per_day
        $user = $detail->user;
        $salary_per_day = $user->salary_per_day ?? 0;
        $detail->total_salary = $detail->total_days * $salary_per_day;
        $detail->save();

        // Cập nhật lại tổng payroll
        $payroll = $detail->payroll;
        $payroll->total = $payroll->details()->sum('total_salary');
        $payroll->save();

        return response()->json([
            'status' => $status,
            'total_days' => $detail->total_days,
            'total_salary' => $detail->total_salary,
            'payroll_total' => $payroll->total,
        ]);
    }

    public function getAttendanceForm()
    {
        $users = \App\Models\User::whereIn('role', [21, 22])->get();
        return view('admin.attendance.modal', compact('users'));
    }

    public function storeAttendance(Request $request)
    {
        $date = $request->input('date'); // yyyy-mm-dd
        $month = date('Y-m', strtotime($date)); // lấy tháng/năm, ví dụ: 2024-07
        $day = (int)date('j', strtotime($date)); // lấy ngày trong tháng (1-31)

        // 1. Kiểm tra payroll tháng/năm đã có chưa, nếu chưa thì tạo mới
        $payroll = Payroll::where('month', $month)->first();
        if (!$payroll) {
            $payroll = Payroll::create([
                'month' => $month,
                'status' => 0,
                'total' => 0,
            ]);
        }

        // 2. Duyệt từng nhân viên được chấm công
        foreach ($request->user_ids as $userId) {
            // Kiểm tra payroll_detail đã có chưa
            $detail = PayrollDetail::where('payroll_id', $payroll->id)
                ->where('user_id', $userId)
                ->first();

            if (!$detail) {
                // Nếu chưa có, tạo mới payroll_detail cho nhân viên này
                $workDays = [$day];
            } else {
                // Nếu đã có, cập nhật work_days
                $workDays = $detail->work_days ? array_map('intval', explode(',', $detail->work_days)) : [];
                if (!in_array($day, $workDays)) {
                    $workDays[] = $day;
                }
            }
            sort($workDays);

            // Lấy lương/ngày của nhân viên
            $user = User::find($userId);
            $salary_per_day = $user->salary_per_day ?? 0;
            $total_salary = count($workDays) * $salary_per_day;

            // Lưu payroll_detail
            if (!$detail) {
                PayrollDetail::create([
                    'payroll_id' => $payroll->id,
                    'user_id' => $userId,
                    'total_days' => count($workDays),
                    'total_salary' => $total_salary,
                    'work_days' => implode(',', $workDays),
                ]);
            } else {
                $detail->work_days = implode(',', $workDays);
                $detail->total_days = count($workDays);
                $detail->total_salary = $total_salary;
                $detail->save();
            }
        }

        // 3. Cập nhật lại tổng total cho payroll
        $payroll->total = $payroll->details()->sum('total_salary');
        $payroll->save();

        return redirect()->back()->with('success', 'Chấm công thành công!');
    }

    public function pay($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->status = 1;
        $payroll->save();
        return redirect()->route('payroll.index')->with('success', 'Thanh toán thành công!');
    }
}
