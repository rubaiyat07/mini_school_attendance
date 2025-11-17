<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AttendanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// **IMPORTANT: Custom student routes MUST come BEFORE apiResource**
Route::get('/students/classes', [StudentController::class, 'getClasses']);
Route::get('/students/sections', [StudentController::class, 'getSections']);

// Student CRUD Routes
Route::apiResource('students', StudentController::class);

// Attendance Routes
Route::get('/attendance', [AttendanceController::class, 'index']);
Route::post('/attendance', [AttendanceController::class, 'store']);
Route::post('/attendance/bulk', [AttendanceController::class, 'bulk']);
Route::get('/attendance/summary/today', [AttendanceController::class, 'todaySummary']);
Route::post('/attendance/report/monthly', [AttendanceController::class, 'monthlyReport']);
Route::get('/attendance/dashboard', [AttendanceController::class, 'dashboardData']);