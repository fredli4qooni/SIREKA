<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{
    /**
     * Menampilkan halaman daftar mata pelajaran.
     */
    public function index()
    {
        $subjects = Subject::orderBy('order')->get();

        return Inertia::render('Subjects/Index', [
            'subjects' => $subjects
        ]);
    }

    /**
     * Menyimpan mata pelajaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'order' => 'required|integer',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('message', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui mata pelajaran.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'order' => 'required|integer',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('message', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mata pelajaran.
     */
    public function destroy(Subject $subject)
    {
        // Cek apakah mapel memiliki data nilai terkait
        if ($subject->learningOutcomes()->exists()) {
            return redirect()->route('subjects.index')->with('error', 'Mata pelajaran tidak dapat dihapus karena sudah memiliki data nilai terkait.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')->with('message', 'Mata pelajaran berhasil dihapus.');
    }

    public function show(Subject $subject)
    {
        // Muat relasi learningGoals agar data TP ikut terkirim
        $subject->load(['learningGoals', 'summativeMaterials']);

        return Inertia::render('Subjects/Show', [
            'subject' => $subject
        ]);

        // Muat kedua relasi

    }
    // Method create, show, dan edit tidak diperlukan karena form ditangani dengan modal di halaman index.
}
