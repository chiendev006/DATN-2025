<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\historylog;
use App\Http\Requests\StorehistorylogRequest;
use App\Http\Requests\UpdatehistorylogRequest;
use Illuminate\Http\Request;

class HistorylogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\historylog::query();

        // Lọc theo khoảng ngày
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Lọc theo role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $perPage = $request->input('per_page', 5);
        $historylog = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.historylog.index', compact('historylog'));
    }



    public function delete($id)
    {
        $histotylog = historylog::find($id);
        $histotylog->delete();
    return redirect()->route('historylog.index');
    }
}
