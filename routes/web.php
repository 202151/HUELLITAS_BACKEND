<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\FichaClinicaController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('fichas-clinicas')->name('fichas-clinicas.')->group(function () {
    Route::get('/', [FichaClinicaController::class, 'index'])->name('index');
    Route::get('/crear', [FichaClinicaController::class, 'create'])->name('create');
    Route::post('/', [FichaClinicaController::class, 'store'])->name('store');
    Route::get('/{id}', [FichaClinicaController::class, 'show'])->name('show');
    Route::get('/{id}/editar', [FichaClinicaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FichaClinicaController::class, 'update'])->name('update');
});
