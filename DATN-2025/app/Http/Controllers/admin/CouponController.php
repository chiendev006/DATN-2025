<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class CouponController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = Coupon::query();

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $now = now();
            if ($request->status == 'active') {
                $query->where(function($q) use ($now) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                })->where('usage_limit', '>', 0);
            } elseif ($request->status == 'expired') {
                $query->whereNotNull('expires_at')->where('expires_at', '<', $now);
            } elseif ($request->status == 'used_up') {
                $query->where('usage_limit', 0);
            } elseif ($request->status == 'not_used') {
                $query->where('used', 0);
            }
        }
        if ($request->filled('starts_at')) {
            $query->whereDate('starts_at', '>=', $request->starts_at);
        }
        if ($request->filled('expires_at')) {
            $query->whereDate('expires_at', '<=', $request->expires_at);
        }
        if ($request->filled('usage_left')) {
            $query->where('usage_limit', '>=', $request->usage_left);
        }

        $coupons = $query->paginate($perPage)->appends($request->except('page'));
        return view('admin.coupon.index', compact('coupons'));
    }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'code' => 'required|string|max:255|unique:coupons,code',
        'discount' => 'required|numeric|min:0',
        'min_order_value' => 'required|numeric|min:0',
        'type' => 'required|in:percent,fixed',
        'usage_limit' => 'required|integer|min:0',
        'starts_at' => 'required|date',
        'expires_at' => 'nullable|date|after_or_equal:starts_at',
    ], [
        'code.required' => 'Vui lòng nhập mã coupon',
        'code.unique' => 'Mã coupon đã tồn tại',
        'discount.required' => 'Vui lòng nhập giá trị giảm giá',
        'discount.numeric' => 'Giá trị giảm giá phải là số',
        'discount.min' => 'Giá trị giảm giá không được nhỏ hơn 0',
        'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu',
        'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số',
        'min_order_value.min' => 'Giá trị đơn tối thiểu không được nhỏ hơn 0',
        'type.required' => 'Vui lòng chọn loại giảm giá',
        'type.in' => 'Loại giảm giá không hợp lệ',
        'usage_limit.required' => 'Vui lòng nhập số lượng',
        'usage_limit.integer' => 'Số lượng phải là số nguyên',
        'usage_limit.min' => 'Số lượng không được nhỏ hơn 0',
        'starts_at.required' => 'Vui lòng chọn ngày bắt đầu',
        'starts_at.date' => 'Ngày bắt đầu không hợp lệ',
        'expires_at.date' => 'Ngày kết thúc không hợp lệ',
        'expires_at.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('showAddCouponModal', true); 
    }

    $time = null;
    if ($request->expires_at) {
        $time = Carbon::parse($request->expires_at);
    }

    Coupon::create([
        'code' => $request->code,
        'discount' => $request->discount,
        'min_order_value' => $request->min_order_value,
        'expires_at' => $time,
        'starts_at' => $request->starts_at,
        'type' => $request->type,
        'usage_limit' => $request->usage_limit,
    ]);

    return redirect()->route('coupon.index')->with('success', 'Coupon added successfully');
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'code' => 'required|string|max:255|unique:coupons,code,' . $id,
        'discount' => 'required|numeric|min:0',
        'min_order_value' => 'required|numeric|min:0',
        'type' => 'required|in:percent,fixed',
        'usage_limit' => 'required|integer|min:0',
        'starts_at' => 'required|date',
        'expires_at' => 'nullable|date|after_or_equal:starts_at',
    ], [
        'code.required' => 'Vui lòng nhập mã coupon',
        'code.unique' => 'Mã coupon đã tồn tại',
        'discount.required' => 'Vui lòng nhập giá trị giảm giá',
        'discount.numeric' => 'Giá trị giảm giá phải là số',
        'discount.min' => 'Giá trị giảm giá không được nhỏ hơn 0',
        'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu',
        'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số',
        'min_order_value.min' => 'Giá trị đơn tối thiểu không được nhỏ hơn 0',
        'type.required' => 'Vui lòng chọn loại giảm giá',
        'type.in' => 'Loại giảm giá không hợp lệ',
        'usage_limit.required' => 'Vui lòng nhập số lượng',
        'usage_limit.integer' => 'Số lượng phải là số nguyên',
        'usage_limit.min' => 'Số lượng không được nhỏ hơn 0',
        'starts_at.required' => 'Vui lòng chọn ngày bắt đầu',
        'starts_at.date' => 'Ngày bắt đầu không hợp lệ',
        'expires_at.date' => 'Ngày kết thúc không hợp lệ',
        'expires_at.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('showEditCouponModal', $id);
    }

    $time = null;
    if($request->expires_at){
        $time = Carbon::parse($request->expires_at);
    }
    Coupon::where('id', $id)->update([
        'code' => $request->code,
        'discount' => $request->discount,
        'min_order_value' => $request->min_order_value,
        'expires_at' =>$time ,
        'starts_at'=>$request->starts_at,
        'type' => $request->type,
        'usage_limit' => $request->usage_limit,
    ]);
    return redirect()->route('coupon.index')->with('success', 'Coupon updated successfully');
}

    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully');
    }
}
