<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningOutcome;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class RaporController extends Controller
{
    /**
     * Mengambil data rapor (capaian pembelajaran) untuk seorang siswa
     * pada tahun ajaran tertentu.
     */
    public function show(Student $student, $academic_year)
    {
        // 1. Ambil semua mata pelajaran
        $subjects = Subject::orderBy('order', 'asc')->get();

        // 2. Ambil data capaian pembelajaran yang sudah ada untuk siswa & tahun ajaran ini
        $outcomes = LearningOutcome::where('student_id', $student->id)
            ->where('academic_year', $academic_year)
            ->get()
            ->keyBy('subject_id'); // Jadikan subject_id sebagai key array agar mudah diakses

        // 3. Gabungkan data
        $raporData = $subjects->map(function ($subject) use ($outcomes) {
            $outcome = $outcomes->get($subject->id);
            return [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'predicate' => $outcome ? $outcome->predicate : null, // Jika sudah ada, tampilkan. Jika tidak, null.
                'description' => $outcome ? $outcome->description : '',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $raporData,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data capaian pembelajaran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string',
            'outcomes' => 'required|array',
            'outcomes.*.subject_id' => 'required|exists:subjects,id',
            'outcomes.*.predicate' => 'required|in:SB,B,C,K',
            'outcomes.*.description' => 'required|string',
        ]);

        $studentId = $request->input('student_id');
        $academicYear = $request->input('academic_year');
        $outcomesData = $request->input('outcomes');

        // Gunakan transaction untuk memastikan semua data tersimpan atau tidak sama sekali
        DB::transaction(function () use ($studentId, $academicYear, $outcomesData) {
            foreach ($outcomesData as $data) {
                LearningOutcome::updateOrCreate(
                    // Kriteria untuk mencari data yang sudah ada
                    [
                        'student_id' => $studentId,
                        'subject_id' => $data['subject_id'],
                        'academic_year' => $academicYear,
                    ],
                    // Data yang akan di-update atau di-create
                    [
                        'predicate' => $data['predicate'],
                        'description' => $data['description'],
                    ]
                );
            }
        });

        return response()->json(['success' => true, 'message' => 'Data rapor berhasil disimpan!'], 201);
    }

    /**
     * Generate dan stream rapor PDF untuk seorang siswa.
     */
    public function cetak(Student $student)
    {
        // 1. Ambil data pengaturan sekolah
        $settings = SchoolSetting::firstOrFail();
        
        // 2. Ambil data guru yang sedang login (termasuk NIP)
        $teacher = User::firstOrFail();

        // 3. Ambil semua mata pelajaran
        $subjects = Subject::orderBy('order')->get();

        // 4. Ambil semua nilai rapor siswa untuk semester & tahun ajaran aktif
        $learningOutcomes = $student->learningOutcomes()
            ->where('academic_year', $settings->academic_year)
            ->where('semester', $settings->semester)
            ->get();
            
        // 5. Ambil data ekskul siswa
        $student->load(['extracurriculars.extracurricular']);
        
        // 6. Data yang akan dikirim ke view
        $data = [
            'student' => $student,
            'settings' => $settings,
            'teacherName' => $teacher->name,
            'teacherNip' => $teacher->nip, // Kirim NIP guru
            'subjects' => $subjects,
            'learningOutcomes' => $learningOutcomes,
        ];

        // Buat PDF menggunakan view 'reports.template' yang sudah kita ubah
        $pdf = Pdf::loadView('reports.template', $data);
        
        $filename = 'Rapor - ' . $student->name . ' - Semester ' . $settings->semester . '.pdf';

        return $pdf->stream($filename);
    }

    public function cetakSampul(Student $student)
    {
        // Ambil data pengaturan sekolah
        $settings = SchoolSetting::firstOrFail();
        
        // Data yang akan dikirim ke view
        $data = [
            'student' => $student,
            'settings' => $settings,
        ];

        // Buat PDF menggunakan view 'reports.cover'
        $pdf = Pdf::loadView('reports.cover', $data);
        
        // Atur nama file PDF
        $filename = 'Sampul Rapor - ' . $student->name . '.pdf';

        return $pdf->stream($filename);
    }

     public function cetakBukuInduk(Student $student)
    {
        // 1. Ambil data pengaturan sekolah
        $settings = SchoolSetting::firstOrFail();
        
        // 2. Ambil semua mata pelajaran yang akan ditampilkan di tabel
        $subjects = Subject::orderBy('order')->get();
        
        // 3. Ambil SEMUA nilai rapor siswa untuk semester & tahun ajaran aktif
        $reportScores = $student->learningOutcomes()
            ->where('academic_year', $settings->academic_year)
            ->where('semester', $settings->semester)
            ->get();
            
        // 4. Ambil data ekskul siswa
        $student->load(['extracurriculars.extracurricular']);

        // 5. Data yang akan dikirim ke view
        $data = [
            'student' => $student,
            'settings' => $settings,
            'subjects' => $subjects,
            'reportScores' => $reportScores, // Kirim collection nilai ini
        ];

        $pdf = Pdf::loadView('reports.main_book', $data);
        $filename = 'Buku Induk - ' . $student->name . '.pdf';
        return $pdf->stream($filename);
    }
}