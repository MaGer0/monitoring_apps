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
    Route::post('/monitorings', [MonitoringController::class, 'store']);
    Route::put('/monitorings/{id}', [MonitoringController::class, 'update'])->middleware('MonitoringOwner');
    Route::delete('/monitorings/{id}', [MonitoringController::class, 'destroy'])->middleware('MonitoringOwner');

    // Detail Student Monitoring
    Route::get('/notpresents', [DetailStudentMonitoringController::class, 'index']);
    Route::post('/notpresents/{id}', [DetailStudentMonitoringController::class, 'store']);
    Route::put('/notpresents/{id}', [DetailStudentMonitoringController::class, 'update'])->middleware('DetailMonitoringOwner');
    Route::delete('/notpresents/{id}', [DetailStudentMonitoringController::class, 'destroy'])->middleware('DetailMonitoringOwner');
});

Route::post('/import', [StudentController::class, 'import']);
