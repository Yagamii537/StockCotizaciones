<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;



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
    Route::resource('users', UserController::class)->names('users');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('permissions', PermissionController::class)->names('permissions');

    Route::resource('inventarios', InventarioController::class)->names('inventarios')->except('show');
    Route::get('/inventarios/search', [InventarioController::class, 'search'])->name('inventarios.search');
    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generatePDF'])->name('cotizaciones.pdf');


    Route::resource('categorias', CategoriaController::class)->names('categorias');
    Route::resource('clientes', ClienteController::class)->names('clientes')->except('show');
    Route::resource('cotizaciones', CotizacionController::class)->names('cotizaciones');
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
});
