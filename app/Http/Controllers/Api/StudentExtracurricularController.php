<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentExtracurricular;
use Illuminate\Http\Request;

class StudentExtracurricularController extends Controller
{
    /**
     * Menyimpan atau memperbarui nilai ekskul siswa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'academic_year' => 'required|string',
            'semester' => 'required|integer|in:1,2',
            'predicate' => 'required|in:Mulai Berkembang,Cukup Berkembang,Berkembang,Sangat Berkembang',
            'description' => 'nullable|string',
        ]);

        StudentExtracurricular::updateOrCreate(
            // Kriteria untuk mencari data yang sudah ada
            [
                'student_id' => $validated['student_id'],
                'extracurricular_id' => $validated['extracurricular_id'],
                'academic_year' => $validated['academic_year'],
                'semester' => $validated['semester'],
            ],
            // Data yang akan di-update atau di-create
            [
                'predicate' => $validated['predicate'],
                'description' => $validated['description'],
            ]
        );

        // Kita akan merespons dengan redirect agar sesuai dengan form Inertia
        return back()->with('message', 'Nilai ekstrakurikuler berhasil disimpan.');
    }
    
    /**
     * Menghapus nilai ekskul siswa.
     */
    public function destroy(StudentExtracurricular $studentExtracurricular)
    {
        $studentExtracurricular->delete();
        
        // Respons redirect agar sesuai dengan form Inertia
        return back()->with('message', 'Penilaian berhasil dihapus.');
    }
}