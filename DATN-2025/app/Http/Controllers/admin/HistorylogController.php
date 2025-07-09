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

        $perPage = $request->input('per_page', 10);
        $historylog = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.historylog.index', compact('historylog'));
    }



    public function delete($id)
    {
        $histotylog = historylog::find($id);
        $histotylog->delete();
    return redirect()->route('historylog.index');
    }

    public function deleteByTime(Request $request)
    {
        $range = $request->input('range');
        $query = \App\Models\historylog::query();

        switch ($range) {
            case '1hour':
                $query->where('created_at', '>=', now()->subHour());
                break;
            case '1day':
                $query->where('created_at', '>=', now()->subDay());
                break;
            case '1week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case '1month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            case 'all':
                // Không cần where, xóa tất cả
                break;
            default:
                return redirect()->route('historylog.index')->with('error', 'Khoảng thời gian không hợp lệ!');
        }

        if ($range === 'all') {
            \App\Models\historylog::truncate();
        } else {
            $query->delete();
        }

        return redirect()->route('historylog.index')->with('success', 'Đã xóa lịch sử thành công!');
    }
}
