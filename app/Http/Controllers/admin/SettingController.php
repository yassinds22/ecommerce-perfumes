<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::all()->groupBy('group');
        
        if ($request->ajax()) {
            return view('admin.sections.settings', compact('settings'))->render();
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الإعدادات بنجاح']);
        }
        
        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
