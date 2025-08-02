<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend.settings.index', [
            'settings' => View::shared('settings'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'late_fee_per_day' => 'required|numeric',
            'lost_fee' => 'required|numeric',
            'max_book_per_rent' => 'required|numeric',
            'max_rent_day' => 'required|numeric',
        ]);

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
