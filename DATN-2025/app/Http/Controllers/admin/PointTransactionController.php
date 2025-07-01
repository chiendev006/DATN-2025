<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class PointTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PointTransaction::with(['user', 'order', 'createdBy']);
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $perPage = $request->input('per_page', 10);
        $transactions = $query->orderByDesc('id')->paginate($perPage);
        $users = User::where('role', 0)->orderBy('name')->get();
        return view('admin.point_transactions.index', compact('transactions', 'users'));
    }

    public function show($id)
    {
        $transaction = PointTransaction::with(['user', 'order', 'createdBy'])->findOrFail($id);
        return view('admin.point_transactions.show', compact('transaction'));
    }
} 