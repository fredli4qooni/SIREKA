<?php

// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Student;
use App\Models\SchoolSetting;
use App\Http\Controllers\Api\RaporController;
use App\Http\Controllers\Api\SettingController;
use App\Models\Subject;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Api\LearningGoalController;
use App\Http\Controllers\Api\SummativeMaterialController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\ReportRecapController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\ExtracurricularController;
use App\Models\Extracurricular;
use App\Http\Controllers\Api\StudentController as ApiStudentController;
use App\Http\Controllers\Api\StudentExtracurricularController; // Pastikan ini ada

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
});

Route::get('/dashboard', function () {
    $stats = [
        'total_students' => Student::count(),
        'total_subjects' => Subject::count(),
    ];
    $school_name = SchoolSetting::first()->school_name ?? 'Sekolah Anda';

    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'schoolName' => $school_name,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/students', function () {
        return Inertia::render('Students/Index');
    })->name('students.page.index');

    Route::get('/rapor/input-capaian', function () {
        return Inertia::render('Rapor/InputCompetence', [ // Ganti nama komponen
            'students' => Student::orderBy('name')->get(['id', 'name']),
            'subjects' => Subject::orderBy('order')->get(['id', 'name']),
            'settings' => SchoolSetting::first(['academic_year', 'semester']),
        ]);
    })->name('rapor.input');

    Route::get('/p5/input', function () {
        return Inertia::render('P5/Input', [
            'students' => Student::orderBy('name', 'asc')->get(['id', 'name']),
            'settings' => SchoolSetting::first(['academic_year']),
        ]);
    })->name('p5.input');

    Route::get('/pengaturan', function () {
        return Inertia::render('Settings/Index');
    })->name('settings.page')->middleware(['auth', 'verified']);

    Route::prefix('api')->group(function () {

        // === API ROUTES DIDAFTARKAN SECARA EKSPLISIT DI SINI ===
        Route::get('/settings', [SettingController::class, 'index'])->name('api.settings.get');
        Route::post('/settings', [SettingController::class, 'update'])->name('api.settings.update');

        Route::post('learning-goals', [LearningGoalController::class, 'store'])->name('api.learning-goals.store');
        Route::put('learning-goals/{learningGoal}', [LearningGoalController::class, 'update'])->name('api.learning-goals.update');
        Route::delete('learning-goals/{learningGoal}', [LearningGoalController::class, 'destroy'])->name('api.learning-goals.destroy');

        Route::post('summative-materials', [SummativeMaterialController::class, 'store'])->name('api.summative-materials.store');
        Route::put('summative-materials/{summativeMaterial}', [SummativeMaterialController::class, 'update'])->name('api.summative-materials.update');
        Route::delete('summative-materials/{summativeMaterial}', [SummativeMaterialController::class, 'destroy'])->name('api.summative-materials.destroy');

        Route::get('/subjects/{subject}', function (App\Models\Subject $subject) {
            $subject->load(['learningGoals', 'summativeMaterials']);
            return response()->json($subject);
        })->name('api.subjects.show');

        Route::get('/scores', [ScoreController::class, 'getScores'])->name('api.scores.get');
        Route::post('/scores', [ScoreController::class, 'saveScores'])->name('api.scores.save');
        Route::post('/attendances/update-all', [AttendanceController::class, 'updateAll'])->name('api.attendances.update_all');

        Route::get('/students/{student}/extracurriculars', [ApiStudentController::class, 'getExtracurriculars'])->name('api.students.extracurriculars');
        Route::post('/student-extracurriculars', [StudentExtracurricularController::class, 'store'])
            ->name('api.student_extracurriculars.store');

        Route::delete('/student-extracurriculars/{studentExtracurricular}', [StudentExtracurricularController::class, 'destroy'])
            ->name('api.student_extracurriculars.destroy');

        Route::get('/competence', [ScoreController::class, 'getCompetence'])->name('api.competence.get');
        Route::post('/competence', [ScoreController::class, 'saveDescription'])->name('api.competence.save');
    });


    Route::get('/rapor/cetak/{student}', [RaporController::class, 'cetak'])->name('rapor.cetak');
    Route::resource('subjects', SubjectController::class);
    Route::get('/report-recap', [ReportRecapController::class, 'index'])->name('report.recap');
    Route::get('/attendances/input', function () {
        // Ambil semua siswa beserta data kehadiran mereka saat ini
        $students = Student::orderBy('name')->get([
            'id',
            'name',
            'nis',
            'attendance_sick',
            'attendance_permission',
            'attendance_alpha'
        ]);
        return Inertia::render('Attendances/Input', [
            'students' => $students,
        ]);
    })->name('attendances.input');
    Route::resource('extracurriculars', ExtracurricularController::class);
    Route::get('/extracurricular-scores/input', function () {
        $settings = SchoolSetting::firstOrFail(['academic_year', 'semester']);
        return Inertia::render('Extracurriculars/ScoreInput', [
            'students' => Student::orderBy('name')->get(['id', 'name']),
            'extracurriculars' => Extracurricular::orderBy('name')->get(['id', 'name']),
            'settings' => $settings,
        ]);
    })->name('extracurriculars.scores.input');
    Route::get('/rapor/cetak-sampul/{student}', [RaporController::class, 'cetakSampul'])->name('rapor.cetak_sampul');
    Route::get('/rapor/cetak-buku-induk/{student}', [RaporController::class, 'cetakBukuInduk'])->name('rapor.cetak_buku_induk');
    // Di dalam grup middleware 'auth'
    Route::get('/scores/input-summative', function () {
        return Inertia::render('Scores/SummativeInput', [
            'students' => Student::orderBy('name')->get(['id', 'name']),
            'subjects' => Subject::orderBy('order')->get(['id', 'name']),
            'settings' => SchoolSetting::first(['academic_year', 'semester']),
        ]);
    })->name('scores.input.summative');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
