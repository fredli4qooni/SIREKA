<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Meskipun tidak digunakan langsung, baik untuk ada
use Illuminate\Validation\Rule; // <-- Import Rule sangat penting

class StudentController extends Controller
{
    /**
     * Menampilkan daftar semua siswa.
     * (GET /api/students)
     */
    public function index()
    {
        // Mengurutkan berdasarkan nama adalah praktik yang baik
        $students = Student::orderBy('name', 'asc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar data siswa',
            'data' => $students
        ], 200);
    }

    /**
     * Menyimpan data siswa baru.
     * (POST /api/students)
     */
    public function store(Request $request)
    {
        // Validasi semua data dari request menggunakan aturan yang terpusat
        $validatedData = $request->validate($this->studentValidationRules());

        // Buat record siswa baru dari data yang sudah tervalidasi
        $student = Student::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $student
        ], 201);
    }

    /**
     * Menampilkan data satu siswa.
     * (GET /api/students/{student})
     */
    public function show(Student $student)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail data siswa',
            'data' => $student
        ], 200);
    }

    /**
     * Memperbarui data siswa.
     * (PUT /api/students/{student})
     */
    public function update(Request $request, Student $student)
    {
        // Validasi semua data dari request, dengan mengabaikan ID siswa saat ini untuk aturan unik
        $validatedData = $request->validate($this->studentValidationRules($student->id));

        // Update record siswa dengan data yang sudah tervalidasi
        $student->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil diperbarui',
            'data' => $student
        ], 200);
    }

    /**
     * Menghapus data siswa.
     * (DELETE /api/students/{student})
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus',
        ], 200);
    }

    /**
     * Method pribadi untuk menampung semua aturan validasi data siswa.
     * Ini membuat kode lebih bersih dan mudah dikelola.
     * @param int|null $studentId ID siswa yang akan diabaikan saat validasi unik (untuk mode update).
     * @return array
     */
    private function studentValidationRules(int $studentId = null): array
    {
        return [
            // Data Pribadi
            'nis' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($studentId)],
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('students')->ignore($studentId)],
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'previous_education' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',

            // Data Orang Tua
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:100',
            'mother_job' => 'nullable|string|max:100',

            // Alamat Orang Tua
            'parent_address_street' => 'nullable|string|max:255',
            'parent_address_village' => 'nullable|string|max:100',
            'parent_address_sub_district' => 'nullable|string|max:100',
            'parent_address_district' => 'nullable|string|max:100',
            'parent_address_province' => 'nullable|string|max:100',

            // Data Wali
            'guardian_name' => 'nullable|string|max:255',
            'guardian_job' => 'nullable|string|max:100',
            'guardian_address' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
        ];
    }

    public function getExtracurriculars(Request $request, Student $student)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'semester' => 'required|integer|in:1,2',
        ]);
        
        // Ambil data dengan memuat relasi ke nama ekskulnya
        $studentExtracurriculars = $student->extracurriculars()
            ->where('academic_year', $validated['academic_year'])
            ->where('semester', $validated['semester'])
            ->with('extracurricular:id,name') // Hanya ambil ID dan nama dari tabel ekskul
            ->get();

        return response()->json($studentExtracurriculars);
    }
}