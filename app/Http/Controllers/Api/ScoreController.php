<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningOutcome;
use App\Models\MaterialScore;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    /**
     * Mengambil data nilai sumatif untuk siswa & mata pelajaran tertentu.
     */
    public function getScores(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
        ]);

        $subject = Subject::find($validated['subject_id']);
        if (!$subject) {
            return response()->json(['message' => 'Mata pelajaran tidak ditemukan.'], 404);
        }

        // 1. Ambil semua materi untuk mata pelajaran ini
        $materials = $subject->summativeMaterials;

        // 2. Ambil nilai per materi yang sudah ada
        $materialScores = MaterialScore::where('student_id', $validated['student_id'])
            ->where('academic_year', $validated['academic_year'])
            ->where('semester', $validated['semester'])
            ->whereIn('summative_material_id', $materials->pluck('id'))
            ->get()->keyBy('summative_material_id');
            
        // 3. Ambil nilai akhir semester yang sudah ada
        $finalOutcome = LearningOutcome::where('student_id', $validated['student_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_year', $validated['academic_year'])
            ->where('semester', $validated['semester'])
            ->first();

        // 4. Gabungkan data menjadi format yang mudah digunakan di frontend
        $scoreData = $materials->map(function ($material) use ($materialScores) {
            $score = $materialScores->get($material->id);
            return [
                'material_id' => $material->id,
                'material_name' => $material->material_name,
                'test_score' => $score ? $score->test_score : null,
                'nontest_score' => $score ? $score->nontest_score : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'material_scores' => $scoreData,
                'final_test_score' => $finalOutcome ? $finalOutcome->final_test_score : null,
                'final_nontest_score' => $finalOutcome ? $finalOutcome->final_nontest_score : null,
                'report_score' => $finalOutcome ? $finalOutcome->report_score : null,
            ]
        ]);
    }

    /**
     * Menyimpan data nilai sumatif dan menghitung nilai rapor.
     */
    public function saveScores(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
            'scores' => 'present|array',
            'scores.*.material_id' => 'required|exists:summative_materials,id',
            'scores.*.test_score' => 'nullable|integer|min:0|max:100',
            'scores.*.nontest_score' => 'nullable|integer|min:0|max:100',
            'final_test_score' => 'nullable|integer|min:0|max:100',
            'final_nontest_score' => 'nullable|integer|min:0|max:100',
        ]);

        DB::transaction(function () use ($validated) {
            $allScores = [];

            // 1. Simpan/Update nilai per materi dan kumpulkan semua nilai
            if (isset($validated['scores'])) {
                foreach ($validated['scores'] as $scoreData) {
                    MaterialScore::updateOrCreate(
                        [
                            'student_id' => $validated['student_id'],
                            'summative_material_id' => $scoreData['material_id'],
                            'academic_year' => $validated['academic_year'],
                            'semester' => $validated['semester'],
                        ],
                        [
                            'test_score' => $scoreData['test_score'] ?? null,
                            'nontest_score' => $scoreData['nontest_score'] ?? null,
                        ]
                    );

                    if (isset($scoreData['test_score'])) $allScores[] = (int)$scoreData['test_score'];
                    if (isset($scoreData['nontest_score'])) $allScores[] = (int)$scoreData['nontest_score'];
                }
            }
            
            // Tambahkan nilai akhir semester ke array
            if (array_key_exists('final_test_score', $validated) && $validated['final_test_score'] !== null) {
                $allScores[] = (int)$validated['final_test_score'];
            }
            if (array_key_exists('final_nontest_score', $validated) && $validated['final_nontest_score'] !== null) {
                $allScores[] = (int)$validated['final_nontest_score'];
            }
            
            // 2. Hitung nilai rapor (rata-rata)
            $reportScore = count($allScores) > 0 ? array_sum($allScores) / count($allScores) : null;
            
            // 3. Simpan nilai akhir semester dan nilai rapor menggunakan Eloquent
            LearningOutcome::updateOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'subject_id' => $validated['subject_id'],
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                ],
                [
                    'final_test_score' => $validated['final_test_score'] ?? null,
                    'final_nontest_score' => $validated['final_nontest_score'] ?? null,
                    'report_score' => $reportScore,
                    'description' => "Deskripsi akan ditambahkan nanti", // Placeholder
                ]
            );
        });

        return response()->json(['success' => true, 'message' => 'Nilai berhasil disimpan.']);
    }

    public function getCompetence(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
        ]);

        $outcome = LearningOutcome::where($validated)->first();

        return response()->json([
            'report_score' => $outcome->report_score ?? null,
            'description' => $outcome->description ?? '',
        ]);
    }

    /**
     * Menyimpan/memperbarui deskripsi capaian kompetensi.
     */
    /**
 * Menyimpan/memperbarui deskripsi capaian kompetensi.
 */
public function saveDescription(Request $request)
{
    // Validasi langsung dari body request
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'subject_id' => 'required|exists:subjects,id',
        'academic_year' => 'required|string',
        'semester' => 'required|in:1,2',
        'description' => 'required|string',
    ]);

    // Karena 'description' ada di $validated, kita bisa langsung menggunakannya.
    LearningOutcome::updateOrCreate(
        // Kriteria pencarian
        [
            'student_id' => $validated['student_id'],
            'subject_id' => $validated['subject_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
        ],
        // Data yang akan di-update atau di-create
        [
            'description' => $validated['description'],
        ]
    );

    // Respons untuk Inertia form helper
    return back()->with('message', 'Capaian kompetensi berhasil disimpan.');
}
}