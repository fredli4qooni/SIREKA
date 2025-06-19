<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController; 
use App\Http\Controllers\Api\RaporController; 
use App\Http\Controllers\Api\P5ProjectController;
use App\Http\Controllers\Api\LearningGoalController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){ // Pastikan dilindungi auth
    Route::post('/learning-goals', [LearningGoalController::class, 'store']);
    Route::put('/learning-goals/{learningGoal}', [LearningGoalController::class, 'update']);
    Route::delete('/learning-goals/{learningGoal}', [LearningGoalController::class, 'destroy']);
});

// Route untuk mengambil data rapor seorang siswa
Route::get('/rapor/{student}/{academic_year}', [RaporController::class, 'show'])->where('academic_year', '.*');

// Route untuk menyimpan data rapor seorang siswa
Route::post('/rapor', [RaporController::class, 'store']);

// Daftarkan Resource Route untuk StudentController
Route::apiResource('students', StudentController::class);

Route::apiResource('students.p5projects', P5ProjectController::class)->shallow();



