<?php

namespace App\Http\Controllers;

use App\Models\LearningOutcome;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportRecapController extends Controller
{
    public function index()
    {
        // 1. Ambil pengaturan aktif
        $settings = SchoolSetting::firstOrFail();
        $academicYear = $settings->academic_year;
        $semester = $settings->semester;

        // 2. Ambil semua siswa dan mata pelajaran
        $students = Student::orderBy('name')->get(['id', 'name', 'nis']);
        $subjects = Subject::orderBy('order')->get(['id', 'name']);

        // 3. Ambil SEMUA nilai rapor yang relevan dalam satu query efisien
        $reportScores = LearningOutcome::where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->get()
            ->keyBy(function ($item) {
                // Buat key unik: "studentId-subjectId" agar mudah dicari
                return $item->student_id . '-' . $item->subject_id;
            });

        // 4. Proses data untuk ditampilkan
        $recapData = $students->map(function ($student) use ($subjects, $reportScores) {
            $studentScores = [];
            $totalScore = 0; 

            foreach ($subjects as $subject) {
                $key = $student->id . '-' . $subject->id;
                $scoreRecord = $reportScores->get($key); // Cari nilai berdasarkan key unik
                
                // Ambil report_score, atau null jika tidak ada record
                $scoreValue = $scoreRecord ? $scoreRecord->report_score : null;

                $studentScores[$subject->id] = $scoreValue;
                $totalScore += (float)$scoreValue; // Tambahkan ke total, anggap 0 jika null
            }

            return [
                'id' => $student->id,
                'nis' => $student->nis,
                'name' => $student->name,
                'scores' => $studentScores, // Ini adalah [subject_id => report_score, ...]
                'total_score' => $totalScore,
            ];
        });

        // 5. Urutkan dan tambahkan ranking
        $sortedRecapData = $recapData->sortByDesc('total_score')->values();
        $rankedData = $sortedRecapData->map(function ($studentData, $index) {
            $studentData['rank'] = $index + 1;
            return $studentData;
        });

        // 6. Kirim data ke view
        return Inertia::render('Scores/ReportRecap', [
            'subjects' => $subjects,
            'recapData' => $rankedData,
            'settings' => $settings,
        ]);
    }
}