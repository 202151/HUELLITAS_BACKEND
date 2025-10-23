<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropietarioController;
use App\Http\Controllers\Api\FichaClinicaController;
use App\Http\Controllers\Api\ReporteController;

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
    });
});
