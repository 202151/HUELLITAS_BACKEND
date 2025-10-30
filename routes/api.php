<?php

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

Route::middleware(['api'])->group(function () {
    Route::apiResource('propietarios', PropietarioController::class);
    Route::apiResource('fichas-clinicas', FichaClinicaController::class)->except(['destroy']);
    Route::get('fichas-clinicas/mascota/{idMascota}', [FichaClinicaController::class, 'fichasPorMascota']);
    
    Route::prefix('reportes')->group(function () {
        Route::get('citas', [ReporteController::class, 'reporteCitas']);
        Route::get('atenciones', [ReporteController::class, 'reporteAtenciones']);
        Route::get('propietarios', [ReporteController::class, 'reportePropietarios']);
        Route::get('mascotas', [ReporteController::class, 'reporteMascotas']);
        Route::get('vacunas', [ReporteController::class, 'reporteVacunas']);
        Route::get('servicios', [ReporteController::class, 'reporteServicios']);
    });
    
    Route::get('/user', function (Request $request) {
        return $request->user();
        //login
    
        Route::post('/login', [UsuarioController::class, 'login']);
        Route::post('/registrar', [UsuarioController::class, 'registrar']);
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'eliminar']);

            // Listar todas las desparasitaciones
        Route::get('/desparasitaciones', [DesparasitacionController::class, 'Lista']);

        // Mostrar una sola desparasitación por ID
        Route::get('/desparasitaciones/{id}', [DesparasitacionController::class, 'Muestra']);

        // Crear una nueva desparasitación
        Route::post('/desparasitaciones', [DesparasitacionController::class, 'Crear']);

        // Actualizar un registro existente
        Route::put('/desparasitaciones/{id}', [DesparasitacionController::class, 'Actualizar']);

        // Eliminar un registro
        Route::post('/desparasitaciones/eliminar/{id}', [DesparasitacionController::class, 'Eliminar']);

    });
});
