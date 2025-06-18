<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\sanpham;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create() {
        return view('client.contact');
    }
   public function store(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255|min:2',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|regex:/^[0-9]{10,11}$/',
        'massage' => 'required|string|min:10|max:1000',
    ], [
        'name.required' => 'Vui lòng nhập họ tên',
        'name.string' => 'Họ tên phải là chuỗi ký tự',
        'name.max' => 'Họ tên không được quá 255 ký tự',
        'name.min' => 'Họ tên phải có ít nhất 2 ký tự',
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không đúng định dạng',
        'email.max' => 'Email không được quá 255 ký tự',
        'phone.regex' => 'Số điện thoại phải có 10-11 chữ số',
        'phone.max' => 'Số điện thoại không được quá 20 ký tự',
        'massage.required' => 'Vui lòng nhập tin nhắn',
        'massage.string' => 'Tin nhắn phải là chuỗi ký tự',
        'massage.min' => 'Tin nhắn phải có ít nhất 10 ký tự',
        'massage.max' => 'Tin nhắn không được quá 1000 ký tự'
    ]);

    Contact::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'massage' => $request->massage,
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Gửi liên hệ thành công!'
        ]);
    }

    return redirect()->route('contact.create')->with('success', 'Thêm thành công!');
}

   
}
