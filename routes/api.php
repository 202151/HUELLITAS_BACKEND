<?php

use App\Http\Controllers\agendacitasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OwnerController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\VaccinationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ActivityLogController;


//Api para agendar citas
Route::post('/Agendar_cita', [agendacitasController::class, 'agendarCita']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user-profile', [AuthController::class, 'userProfile']);
});

Route::middleware(['auth:api', 'log.activity'])->group(function () {
    Route::apiResource('owners', OwnerController::class);
    Route::apiResource('pets', PetController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('medical-records', MedicalRecordController::class);
    Route::apiResource('vaccinations', VaccinationController::class);
    
    // Rutas para reportes
    Route::prefix('reports')->group(function () {
        Route::get('appointments', [ReportController::class, 'appointmentsReport']);
        Route::get('owners', [ReportController::class, 'ownersReport']);
        Route::get('pets', [ReportController::class, 'petsReport']);
        Route::get('medical-records', [ReportController::class, 'medicalRecordsReport']);
        Route::get('vaccinations', [ReportController::class, 'vaccinationsReport']);
        Route::get('services', [ReportController::class, 'servicesReport']);
    });
    
    // Rutas para logs de actividad
    Route::apiResource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});