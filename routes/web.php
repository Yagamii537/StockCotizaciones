<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;

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
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/create', [InventarioController::class, 'create'])->name('inventario.create');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
});
