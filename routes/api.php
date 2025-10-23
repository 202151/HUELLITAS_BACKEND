<?php

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
use App\Http\Controllers\MascotasController;
use App\Http\Controllers\VacunaController;

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

//rutas para el crud de mascotas 
Route::prefix('mascotas')->group(function () {
    Route::get('/', [MascotasController::class, 'index']); 
    Route::post('/', [MascotasController::class, 'store']);
    Route::get('/{id}', [MascotasController::class, 'show']);
    Route::put('/{id}', [MascotasController::class, 'update']);
    Route::delete('/{id}', [MascotasController::class, 'destroy']);
});

//rutas para el crud de vacunas

Route::prefix('vacunas')->group(function () {
    
    // Estadísticas generales
    Route::get('estadisticas', [VacunaController::class, 'estadisticas']);
    // GET /api/vacunas/estadisticas
    // Vacunas próximas a vencer
    Route::get('proximas', [VacunaController::class, 'proximasAVencer']);
    // GET /api/vacunas/proximas
    // Vacunas vencidas
    Route::get('vencidas', [VacunaController::class, 'vencidas']);
    // GET /api/vacunas/vencidas
    // Historial de vacunas por mascota
    Route::get('mascota/{idMascota}', [VacunaController::class, 'historialMascota']);
    // GET /api/vacunas/mascota/1
    
    // Listar todas las vacunas (con filtros opcionales)
    Route::get('/', [VacunaController::class, 'index']);
    // Crear nueva vacuna
    Route::post('/', [VacunaController::class, 'store']);
    // POST /api/vacunas
    // Ver una vacuna específica
    Route::get('/{id}', [VacunaController::class, 'show']);
    // GET /api/vacunas/1
    // Actualizar vacuna
    Route::put('/{id}', [VacunaController::class, 'update']);
    // PUT /api/vacunas/1
    Route::patch('/{id}', [VacunaController::class, 'update']);
    // PATCH /api/vacunas/1
    // Eliminar vacuna
    Route::delete('/{id}', [VacunaController::class, 'destroy']);
    // DELETE /api/vacunas/1
    Route::post('/{id}/aplicar-dosis', [VacunaController::class, 'aplicarProximaDosis']);
    // POST /api/vacunas/1/aplicar-dosis
    
});