<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private const KEYS = [
        'store_name',
        'store_address',
        'store_phone',
        'receipt_footer',
    ];

    public function index()
    {
        $settings = Setting::whereIn('setting_key', self::KEYS)->pluck('setting_value', 'setting_key');

        return view('settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:20'],
            'receipt_footer' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
