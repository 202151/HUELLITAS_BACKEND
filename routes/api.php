<?php

use App\Http\Controllers\agendacitasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropietarioController;
use App\Http\Controllers\Api\FichaClinicaController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\OwnerController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\VaccinationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\DesparasitacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MascotasController;
use App\Http\Controllers\VacunaController;

//Api para agendar citas
Route::post('/Agendar_cita', [agendacitasController::class, 'agendarCita']);
//Api para obtener la lista de citas mas recientes
Route::get(uri: '/obtener_citas', action: [agendacitasController::class, 'obtenerCitas']);
//Api para obtener citas por filtros
Route::get(uri: '/obtener_citas_filtos', action: [agendacitasController::class, 'obtenerCitasFiltradas']);

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

    // Rutas de usuarios
    Route::post('/login', [UsuarioController::class, 'login']);
    Route::post('/registrar', [UsuarioController::class, 'registrar']);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'eliminar']);

    // Rutas de desparasitación
    Route::get('/desparasitaciones', [DesparasitacionController::class, 'Lista']);
    Route::get('/desparasitaciones/{id}', [DesparasitacionController::class, 'Muestra']);
    Route::post('/desparasitaciones', [DesparasitacionController::class, 'Crear']);
    Route::put('/desparasitaciones/{id}', [DesparasitacionController::class, 'Actualizar']);
    Route::post('/desparasitaciones/eliminar/{id}', [DesparasitacionController::class, 'Eliminar']);
});

// Rutas para el crud de mascotas (sin middleware de auth para acceso público)
Route::prefix('mascotas')->group(function () {
    Route::get('/', [MascotasController::class, 'index']); 
    Route::post('/', [MascotasController::class, 'store']);
    Route::get('/{id}', [MascotasController::class, 'show']);
    Route::put('/{id}', [MascotasController::class, 'update']);
    Route::delete('/{id}', [MascotasController::class, 'destroy']);
});

// Rutas para el crud de vacunas
Route::prefix('vacunas')->group(function () {
    // Estadísticas generales
    Route::get('estadisticas', [VacunaController::class, 'estadisticas']);
    // Vacunas próximas a vencer
    Route::get('proximas', [VacunaController::class, 'proximasAVencer']);
    // Vacunas vencidas
    Route::get('vencidas', [VacunaController::class, 'vencidas']);
    // Historial de vacunas por mascota
    Route::get('mascota/{idMascota}', [VacunaController::class, 'historialMascota']);
    
    // CRUD básico
    Route::get('/', [VacunaController::class, 'index']);
    Route::post('/', [VacunaController::class, 'store']);
    Route::get('/{id}', [VacunaController::class, 'show']);
    Route::put('/{id}', [VacunaController::class, 'update']);
    Route::patch('/{id}', [VacunaController::class, 'update']);
    Route::delete('/{id}', [VacunaController::class, 'destroy']);
    
    // Aplicar próxima dosis
    Route::post('/{id}/aplicar-dosis', [VacunaController::class, 'aplicarProximaDosis']);
});
