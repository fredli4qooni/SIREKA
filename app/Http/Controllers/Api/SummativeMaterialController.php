<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SummativeMaterial;
use Illuminate\Http\Request;

class SummativeMaterialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'material_name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        SummativeMaterial::create($validated);
        return back()->with('message', 'Materi Sumatif berhasil ditambahkan.');
    }

    public function update(Request $request, SummativeMaterial $summativeMaterial)
    {
        $validated = $request->validate([
            'material_name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);
        
        $summativeMaterial->update($validated);
        return back()->with('message', 'Materi Sumatif berhasil diperbarui.');
    }

    public function destroy(SummativeMaterial $summativeMaterial)
    {
        // Tambahkan validasi jika materi sudah punya nilai
        // Untuk saat ini, kita langsung hapus
        $summativeMaterial->delete();
        return back()->with('message', 'Materi Sumatif berhasil dihapus.');
    }
}