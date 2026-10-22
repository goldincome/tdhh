<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function index(){
        $settings = GeneralSetting::all();
        return view('admin.account.general-setting.list', compact('settings'));
    }

    public function edit(Request $request){
        $setting = GeneralSetting::find($request->settingId);
        return view('admin.account.general-setting.edit', compact('setting'));
    }

    public function create(Request $request){
        return view('admin.account.general-setting.create');
    }

    public function store(Request $request){
        GeneralSetting::updateOrCreate(
            ['name' => $request->name],
            [
                'value' => $request->value,
                'description' => $request->description,
            ]
       );
       $this->flashSuccessMessage('New Setting Created');
        return redirect()->route('admin.general-setting.list');
    }

    public function update(Request $request){
        GeneralSetting::where('id', $request->settingId )->update([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
        ]);
       $this->flashSuccessMessage('Setting Updated');
        return redirect()->route('admin.general-setting.list');
    }
}
