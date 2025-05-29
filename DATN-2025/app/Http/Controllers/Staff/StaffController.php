<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function login()
    {
        return view('staff.login');
    }
    public function index()
    {
        return view('staff.menu');
    }
}
