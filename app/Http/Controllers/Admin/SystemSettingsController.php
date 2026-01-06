<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index()
    {
        // Pluck key-value pairs for easy access in view
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        return view('pages.admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'logo_instansi']);

        // 1. Update Text Settings
        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // 2. Handle Logo Upload
        if ($request->hasFile('logo_instansi')) {
            $file = $request->file('logo_instansi');
            $filename = 'logo-instansi-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store in public/images folder (or storage link)
            $file->move(public_path('images'), $filename);
            
            // Update DB
            SystemSetting::updateOrCreate(
                ['key' => 'logo_instansi'],
                ['value' => 'images/' . $filename]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan instansi berhasil diperbarui.');
    }
}
