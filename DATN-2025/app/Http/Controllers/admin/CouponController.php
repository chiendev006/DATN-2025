<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index(Request $request  )
    {
        $perPage = $request->input('per_page', 10);
        $coupons = Coupon::paginate($perPage);
        return view('admin.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {


       $coupon = Coupon::create([
        'code' => $request->code,
        'discount' => $request->discount,
        'min_order_value' => $request->min_order_value,
        'starts_at'=> $request->starts_at,
        'expires_at' => $request->expires_at,
        'type' => $request->type,
        'usage_limit' => $request->usage_limit,
       ]);

        return redirect()->route('coupon.index')->with('success', 'Coupon created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required',
            'discount' => 'required',
            'min_order_value' => 'required',
            'expires_at' => 'required',
            'type' => 'required',
            'usage_limit' => 'required',
        ]);
      Coupon::where('id', $id)->update([
        'code' => $request->code,
        'discount' => $request->discount,
        'min_order_value' => $request->min_order_value,
        'expires_at' => $request->expires_at,
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