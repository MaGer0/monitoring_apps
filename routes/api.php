<?php

use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/monitorings', [MonitoringController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/monitorings', [MonitoringController::class, 'store']);
    Route::get('/teachers/@me', [TeacherController::class, 'me']);
});

Route::post('/import', [StudentController::class, 'import']);
