<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointSetting;

class PointSettingController extends Controller
{
    public function index(Request $request)
    {
        $query = PointSetting::query();
        if ($request->filled('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }
        $pointSettings = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.point_settings.index', compact('pointSettings'));
    }

    public function create()
    {
        return view('admin.point_settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:point_settings,key',
            'value' => 'required',
            'description' => 'nullable',
        ]);
        PointSetting::create($request->only('key', 'value', 'description'));
        return redirect()->route('admin.point_settings.index')->with('success', 'Thêm cấu hình điểm thành công!');
    }

    public function edit($id)
    {
        $pointSetting = PointSetting::findOrFail($id);
        return view('admin.point_settings.edit', compact('pointSetting'));
    }

    public function update(Request $request, $id)
    {
        $pointSetting = PointSetting::findOrFail($id);
        $request->validate([
            'key' => 'required|unique:point_settings,key,' . $id,
            'value' => 'required',
            'description' => 'nullable',
        ]);
        $pointSetting->update($request->only('key', 'value', 'description'));
        return redirect()->route('admin.point_settings.index')->with('success', 'Cập nhật cấu hình điểm thành công!');
    }

    public function destroy($id)
    {
        $pointSetting = PointSetting::findOrFail($id);
        $pointSetting->delete();
        return redirect()->route('admin.point_settings.index')->with('success', 'Xóa cấu hình điểm thành công!');
    }
} 