<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\P5Project;
use App\Models\Student;
use Illuminate\Http\Request;

class P5ProjectController extends Controller
{
    /**
     * Menampilkan daftar P5 Projects untuk seorang siswa.
     * GET /api/students/{student}/p5projects
     */
    public function index(Student $student)
    {
        $projects = $student->p5Projects()->get();
        return response()->json(['success' => true, 'data' => $projects]);
    }

    /**
     * Menyimpan P5 Project baru untuk seorang siswa.
     * POST /api/students/{student}/p5projects
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project = $student->p5Projects()->create($validated);

        return response()->json(['success' => true, 'message' => 'Proyek P5 berhasil disimpan.', 'data' => $project], 201);
    }

    /**
     * Menampilkan detail satu P5 Project.
     * GET /api/p5projects/{p5project}
     */
    public function show(P5Project $p5project)
    {
        return response()->json(['success' => true, 'data' => $p5project]);
    }

    /**
     * Memperbarui P5 Project.
     * PUT /api/p5projects/{p5project}
     */
    public function update(Request $request, P5Project $p5project)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $p5project->update($validated);

        return response()->json(['success' => true, 'message' => 'Proyek P5 berhasil diperbarui.', 'data' => $p5project]);
    }

    /**
     * Menghapus P5 Project.
     * DELETE /api/p5projects/{p5project}
     */
    public function destroy(P5Project $p5project)
    {
        $p5project->delete();
        return response()->json(['success' => true, 'message' => 'Proyek P5 berhasil dihapus.']);
    }
}