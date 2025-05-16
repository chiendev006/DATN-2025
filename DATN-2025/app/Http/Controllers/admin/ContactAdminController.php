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
}
