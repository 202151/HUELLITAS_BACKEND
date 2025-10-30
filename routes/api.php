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


//Api para agendar citas
Route::post('/Agendar_cita', [agendacitasController::class, 'agendarCita']);
//Api para obtener la lista de citas mas recientes
Route::get(uri: '/obtener_citas', action: [agendacitasController::class, 'obtenerCitas']);
//Api para obtener citas por filtros
Route::get(uri: '/obtener_citas_filtos', action: [agendacitasController::class, 'obtenerCitasFiltradas']);
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
