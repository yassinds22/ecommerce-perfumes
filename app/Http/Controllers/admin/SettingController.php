<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * @var SettingService
     */
    protected $settingService;

    /**
     * SettingController constructor.
     *
     * @param SettingService $settingService
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $settings = $this->settingService->getGroupedSettings();
        
        if ($request->ajax()) {
            return view('admin.sections.settings', compact('settings'))->render();
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->settingService->updateSettings($request->except('_token'));
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الإعدادات بنجاح']);
        }
        
        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
