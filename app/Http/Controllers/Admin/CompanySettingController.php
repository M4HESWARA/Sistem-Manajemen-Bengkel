<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    public function edit()
    {
        $setting = CompanySetting::current();

        return view('admin.settings.bengkel', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_bengkel' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
        ]);

        CompanySetting::current()->update($data);

        return back()->with('success', 'Info bengkel berhasil diperbarui.');
    }
}
