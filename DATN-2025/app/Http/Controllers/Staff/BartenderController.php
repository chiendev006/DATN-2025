<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BartenderController extends Controller
{
    public function index()
    {
        // Check for flash messages
        $message = session('message');
        return view('staff.bartender', compact('message'));
    }

    public function create()
    {
        return view('staff.bartender');
    }
}
?>
