<?php

use App\Http\Controllers\agendacitasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropietarioController;
use App\Http\Controllers\Api\FichaClinicaController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\servicios;
use App\Http\Controllers\DesparasitacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MascotasController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\PdfCitasController;

// Rutas de autenticación
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

// Rutas de agendar citas
Route::post('/Agendar_cita', [agendacitasController::class, 'agendarCita']);
Route::get('/obtener_citas', [agendacitasController::class, 'obtenerCitas']);
Route::get('/obtener_citas_filtos', [agendacitasController::class, 'obtenerCitasFiltradas']);

// Rutas de usuarios
Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/registrar', [UsuarioController::class, 'registrar']);

// Rutas protegidas
Route::middleware(['auth:api', 'log.activity'])->group(function () {
    
    // Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'eliminar']);
    
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Propietarios
    Route::apiResource('propietarios', PropietarioController::class);
    
    // Fichas Clínicas
    Route::apiResource('fichas-clinicas', FichaClinicaController::class);
    Route::get('/fichas-clinicas/mascota/{idMascota}', [FichaClinicaController::class, 'fichasPorMascota']);
    
    // Servicios
    Route::apiResource('servicios', servicios::class);
    
    // Desparasitaciones
    Route::get('/desparasitaciones', [DesparasitacionController::class, 'Lista']);
    Route::get('/desparasitaciones/{id}', [DesparasitacionController::class, 'Muestra']);
    Route::post('/desparasitaciones', [DesparasitacionController::class, 'Crear']);
    Route::put('/desparasitaciones/{id}', [DesparasitacionController::class, 'Actualizar']);
    Route::post('/desparasitaciones/eliminar/{id}', [DesparasitacionController::class, 'Eliminar']);
    
    // Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('citas', [ReporteController::class, 'reporteCitas']);
        Route::get('atenciones', [ReporteController::class, 'reporteAtenciones']);
        Route::get('propietarios', [ReporteController::class, 'reportePropietarios']);
        Route::get('mascotas', [ReporteController::class, 'reporteMascotas']);
        Route::get('vacunas', [ReporteController::class, 'reporteVacunas']);
        Route::get('servicios', [ReporteController::class, 'reporteServicios']);
    });
});

// Rutas públicas de mascotas
Route::prefix('mascotas')->group(function () {
    Route::get('/', [MascotasController::class, 'index']); 
    Route::post('/', [MascotasController::class, 'store']);
    Route::get('/{id}', [MascotasController::class, 'show']);
    Route::put('/{id}', [MascotasController::class, 'update']);
    Route::delete('/{id}', [MascotasController::class, 'destroy']);
});

// Rutas públicas de vacunas
Route::prefix('vacunas')->group(function () {
    Route::get('estadisticas', [VacunaController::class, 'estadisticas']);
    Route::get('proximas', [VacunaController::class, 'proximasAVencer']);
    Route::get('vencidas', [VacunaController::class, 'vencidas']);
    Route::get('mascota/{idMascota}', [VacunaController::class, 'historialMascota']);
    
    Route::get('/', [VacunaController::class, 'index']);
    Route::post('/', [VacunaController::class, 'store']);
    Route::get('/{id}', [VacunaController::class, 'show']);
    Route::put('/{id}', [VacunaController::class, 'update']);
    Route::patch('/{id}', [VacunaController::class, 'update']);
    Route::delete('/{id}', [VacunaController::class, 'destroy']);
    
    Route::post('/{id}/aplicar-dosis', [VacunaController::class, 'aplicarProximaDosis']);
});

// Reporte PDF de citas
Route::get('/reporteCitas', [PdfCitasController::class, 'reporteCitas']);
