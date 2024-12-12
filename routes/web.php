<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;


// Redirige la raíz al login si no se ha iniciado sesión
Route::get('/', function () {
    return redirect()->route('login');
});

// Grupo de rutas protegidas con autenticación
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Página principal después del login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('inventarios', InventarioController::class)->names('inventarios');
    Route::resource('categorias', CategoriaController::class)->names('categorias');
    Route::resource('clientes', ClienteController::class)->names('clientes');
    Route::resource('cotizaciones', CotizacionController::class)->names('cotizaciones');
});
