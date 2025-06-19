<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningGoal;
use Illuminate\Http\Request;

class LearningGoalController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'goal_description' => 'required|string',
            'scope' => 'required|string|max:255',
        ]);

        LearningGoal::create($validated);
        return back()->with('message', 'Tujuan Pembelajaran berhasil ditambahkan.');

    }

    public function update(Request $request, LearningGoal $learningGoal)
    {
        $validated = $request->validate([
            'goal_description' => 'required|string',
            'scope' => 'required|string|max:255',
        ]);
        
        $learningGoal->update($validated);

        // GANTI response()->json(...) DENGAN INI
        return back()->with('message', 'Tujuan Pembelajaran berhasil diperbarui.');
    }

    public function destroy(LearningGoal $learningGoal)
    {
        $learningGoal->delete();
        return back()->with('message', 'Tujuan Pembelajaran berhasil dihapus.');
    }
}