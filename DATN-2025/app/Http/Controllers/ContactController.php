<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\historylog;
use App\Models\sanpham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        'message' => 'required|string|min:10|max:1000',
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
        'message.required' => 'Vui lòng nhập tin nhắn',
        'message.string' => 'Tin nhắn phải là chuỗi ký tự',
        'message.min' => 'Tin nhắn phải có ít nhất 10 ký tự',
        'message.max' => 'Tin nhắn không được quá 1000 ký tự'
    ]);
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();

        $title = '<strong>Liên hệ mới:</strong> <br>';
        $content1 = " *<span style='color: red;'>Tên:</span> {$contact->name} <br>";
        $content2 = " *<span style='color: red;'>Email:</span> {$contact->email} <br>";
        $content3 = " *<span style='color: red;'>Số điện thoại:</span> {$contact->phone} <br>";
        $content4 = " *<span style='color: red;'>Tin nhắn:</span> {$contact->message} <br>";

        $user_id = Auth::check() ? Auth::user()->id : null;
        historylog::create([
            'user_id' => $user_id,
            'role' => 0,
            'content' => $title . $content1 . $content2 . $content3 . $content4,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Gửi liên hệ thành công!'
            ]);
        }

        return redirect()->route('contact.create')->with('success', 'Gửi liên hệ thành công!');
    }
}
