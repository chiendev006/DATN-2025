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
        'name' => 'required|string',
        'email' => 'required|string|email',
        'massage' => 'required|string',
    ]);

    Contact::create([
        'name' => $request->name,
        'email' => $request->email,
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
