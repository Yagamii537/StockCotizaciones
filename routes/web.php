<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;

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
    Route::resource('users', UserController::class)->middleware('can:users.index')->names('users');
    Route::resource('roles', RoleController::class)->middleware('can:users.index')->names('roles');
    Route::resource('permissions', PermissionController::class)->middleware('can:users.index')->names('permissions');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('inventarios', InventarioController::class)->names('inventarios')->except('show');
    Route::get('/inventarios/search', [InventarioController::class, 'search'])->name('inventarios.search');
    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generatePDF'])->name('cotizaciones.pdf');
    Route::get('/cotizaciones/entregado', [CotizacionController::class, 'entregado'])->name('cotizaciones.entregado');


    Route::resource('categorias', CategoriaController::class)->names('categorias');
    Route::resource('clientes', ClienteController::class)->names('clientes')->except('show');
    Route::resource('cotizaciones', CotizacionController::class)->names('cotizaciones');
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');

    Route::get('/reportes/productos', [ReporteController::class, 'index'])->name('reportes.productos');
    Route::post('/reportes/productos', [ReporteController::class, 'filtrar'])->name('reportes.productos.filtrar');
    Route::post('/reportes/productos/filtrar', [ReporteController::class, 'filtrar'])->name('reportes.productos.filtrar');
});
