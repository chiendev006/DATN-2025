<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\historylog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactAdminController extends Controller
{
    public function index() {
        $contact = Contact::paginate(5);
        return view('admin.contact.index',compact('contact'));
    }
    public function delete($id) {
        $contact=Contact::find($id);
        $content1='';
        $content2='';
        $content3='';
        $content4='';
        $title='<strong>Xóa bài viết:</strong> <br>';
            $content1=" *<span style='color: red;'>Tên:</span> `$contact->name` <br>";
            $content1=" *<span style='color: red;'>Tên:</span> `$contact->email` <br>";
            $content1=" *<span style='color: red;'>Tên:</span> `$contact->phone` <br>";
            $content1=" *<span style='color: red;'>Tên:</span> `$contact->message` <br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
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
