<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Update data kehadiran untuk beberapa siswa sekaligus.
     */
    public function updateAll(Request $request)
    {
        // Validasi data yang masuk
        // 'attendances' harus berupa array, dan setiap item di dalamnya juga array
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.id' => 'required|exists:students,id',
            'attendances.*.attendance_sick' => 'required|integer|min:0',
            'attendances.*.attendance_permission' => 'required|integer|min:0',
            'attendances.*.attendance_alpha' => 'required|integer|min:0',
        ]);

        // Gunakan transaksi untuk memastikan semua data terupdate atau tidak sama sekali
        DB::transaction(function () use ($validated) {
            foreach ($validated['attendances'] as $data) {
                // Cari siswa berdasarkan ID dan update kolom kehadirannya
                Student::where('id', $data['id'])->update([
                    'attendance_sick' => $data['attendance_sick'],
                    'attendance_permission' => $data['attendance_permission'],
                    'attendance_alpha' => $data['attendance_alpha'],
                ]);
            }
        });
        
        return response()->json(['success' => true, 'message' => 'Data kehadiran berhasil diperbarui.']);
    }
}