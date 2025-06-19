<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    /**
     * Mengambil data pengaturan saat ini.
     */
    public function index()
    {
        $schoolSetting = SchoolSetting::firstOrFail();
        $user = Auth::user();

        // Data user yang dikirim sekarang termasuk NIP
        $userData = $user->only(['name', 'nip', 'email']);

        return response()->json([
            'success' => true,
            'data' => [
                'school' => $schoolSetting,
                'user' => $userData,
            ]
        ]);
    }

    public function update(Request $request)
    {
        // Validasi data baru
        $request->validate([
            // Data Sekolah
            'school_name' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:20',
            'nss' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'village' => 'nullable|string|max:100',
            'sub_district' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            // Data Kepsek
            'headmaster_name' => 'nullable|string|max:255',
            'headmaster_nip' => 'nullable|string|max:30',
            // Data Rapor
            'class_level' => 'required|string|max:10',
            'phase' => 'required|string|max:5',
            'semester' => 'required|in:1,2',
            'academic_year' => 'required|string|max:20',
            'report_place' => 'required|string|max:100',
            'report_date' => 'required|date',
            // Data Guru
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'user_email' => 'required|email|max:255',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update data sekolah
        $schoolSetting = SchoolSetting::first();
        $schoolSetting->update($request->except(['name', 'nip', 'user_email', 'password', 'password_confirmation']));

        // Update data guru
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->nip = $request->input('nip');
        $user->email = $request->input('user_email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        return response()->json(['success' => true, 'message' => 'Pengaturan berhasil diperbarui.']);
    }
}
