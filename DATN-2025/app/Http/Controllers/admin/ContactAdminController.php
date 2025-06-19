<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactAdminController extends Controller
{
    public function index() {
        $contact = Contact::paginate(5);
        return view('admin.contact.index',compact('contact'));
    }
    public function delete($id) {
        Contact::destroy($id);
        return redirect()->route('contact.index')->with('success', 'Xóa thành công!');
    }
    public function search(Request $request)
{
    $query = Contact::query();

    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('email')) {
        $query->where('email', 'like', '%' . $request->email . '%');
    }

    if ($request->filled('phone')) {
        $query->where('phone', 'like', '%' . $request->phone . '%');
    }

    $contact = $query->paginate(10)->appends($request->all()); // Giữ lại input khi chuyển trang

    return view('admin.contact.index', compact('contact'));
}


}
