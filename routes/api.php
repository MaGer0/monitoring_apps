<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DetailStudentMonitoringController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout
    Route::get('/logout', [AuthenticationController::class, 'logout']);

    // Current Teacher
    Route::get('/teachers/@me', [TeacherController::class, 'me']);

    // Monitoring
    Route::get('/monitorings', [MonitoringController::class, 'index']);
    Route::get('/monitorings/{id}', [MonitoringController::class, 'show'])->middleware('MonitoringOwner');
    Route::post('/monitorings', [MonitoringController::class, 'store']);
    Route::put('/monitorings/{id}', [MonitoringController::class, 'update'])->middleware('MonitoringOwner');
    Route::delete('/monitorings/{id}', [MonitoringController::class, 'destroy'])->middleware('MonitoringOwner');
    Route::post('/export/sheet', [MonitoringController::class, 'exportXLSX']);
    Route::post('/export/pdf', [MonitoringController::class, 'exportDOMPDF']);

    // Image
    Route::put('/monitorings/{id}/image', [MonitoringController::class, 'changeImage'])->middleware('MonitoringOwner');
    Route::delete('/monitorings/{id}/image', [MonitoringController::class, 'destroyImage'])->middleware('MonitoringOwner');

    // Detail Student Monitoring
    Route::get('/notpresents/{id}', [DetailStudentMonitoringController::class, 'index'])->middleware('DetailMonitoringOwner');
    Route::post('/notpresents/{id}', [DetailStudentMonitoringController::class, 'store'])->middleware('DetailMonitoringOwner');
    Route::put('/notpresents/{id}', [DetailStudentMonitoringController::class, 'update'])->middleware('DetailMonitoringOwner');
    Route::delete('/notpresents/{id}', [DetailStudentMonitoringController::class, 'destroy'])->middleware('DetailMonitoringOwner');

    // Student
    Route::post('/students/import', [StudentController::class, 'import']);
    Route::get('/students', [StudentController::class, 'index']);
});
