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
        // Validasi data (sudah benar, tapi kita perbaiki validasi email)
        $validatedData = $request->validate([
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
            'report_date' => 'required|date',
            // Data Guru
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'user_email' => 'required|email|max:255|unique:users,email,' . Auth::id(), // Validasi email yang lebih baik
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // --- MULAI PERUBAHAN DI SINI ---

        // Update data sekolah dengan cara yang aman menggunakan only()
        $schoolSetting = SchoolSetting::firstOrFail();
        $schoolSetting->update([
            'school_name' => $validatedData['school_name'],
            'npsn' => $validatedData['npsn'],
            'nss' => $validatedData['nss'],
            'address' => $validatedData['address'],
            'postal_code' => $validatedData['postal_code'],
            'village' => $validatedData['village'],
            'sub_district' => $validatedData['sub_district'],
            'district' => $validatedData['district'],
            'province' => $validatedData['province'],
            'email' => $validatedData['email'],
            'headmaster_name' => $validatedData['headmaster_name'],
            'headmaster_nip' => $validatedData['headmaster_nip'],
            'class_level' => $validatedData['class_level'],
            'phase' => $validatedData['phase'],
            'semester' => $validatedData['semester'],
            'academic_year' => $validatedData['academic_year'],
            'report_date' => $validatedData['report_date'],
        ]);

        // Update data guru
        $user = Auth::user();
        $user->name = $validatedData['name'];
        $user->nip = $validatedData['nip'];
        $user->email = $validatedData['user_email'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        // --- BATAS PERUBAHAN ---

        // Respons ini sudah benar untuk Axios, JANGAN ganti ke `return back()`
        return response()->json(['success' => true, 'message' => 'Pengaturan berhasil diperbarui.']);
    }
}
