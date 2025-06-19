<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular; // <-- Import model
use Illuminate\Http\Request;
use Inertia\Inertia; // <-- Import Inertia

class ExtracurricularController extends Controller
{
    /**
     * Menampilkan halaman daftar ekstrakurikuler.
     * Method ini dipanggil saat mengakses GET /extracurriculars
     */
    public function index()
    {
        $extracurriculars = Extracurricular::orderBy('name')->get();

        return Inertia::render('Extracurriculars/Index', [
            'extracurriculars' => $extracurriculars,
        ]);
    }

    /**
     * Menyimpan ekstrakurikuler baru ke database.
     * Method ini dipanggil saat form tambah di-submit (POST /extracurriculars)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:extracurriculars,name',
        ]);

        Extracurricular::create($request->all());

        return back()->with('message', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    /**
     * Memperbarui data ekstrakurikuler.
     * Method ini dipanggil saat form edit di-submit (PUT /extracurriculars/{id})
     */
    public function update(Request $request, Extracurricular $extracurricular)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:extracurriculars,name,' . $extracurricular->id,
        ]);

        $extracurricular->update($request->all());

        return back()->with('message', 'Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Menghapus data ekstrakurikuler.
     * Method ini dipanggil saat tombol hapus diklik (DELETE /extracurriculars/{id})
     */
    public function destroy(Extracurricular $extracurricular)
    {
        // Di sini kita bisa menambahkan validasi, misalnya jangan hapus
        // jika sudah ada siswa yang mengambil ekskul ini. Untuk sekarang, kita langsung hapus.

        $extracurricular->delete();

        return back()->with('message', 'Ekstrakurikuler berhasil dihapus.');
    }
}