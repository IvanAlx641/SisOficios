<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirstLoginController;
use App\Http\Controllers\TipoRequerimientoController;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\SistemaController;
// --- IMPORTAMOS LOS CONTROLADORES DE OFICIOS ---
use App\Http\Controllers\OficioController;
use App\Http\Controllers\OficioSolicitanteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === 1. RUTAS PÚBLICAS ===
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// === 2. RUTAS PROTEGIDAS ===
Route::middleware(['auth'])->group(function () {

    Route::get('/cambiar-password', [FirstLoginController::class, 'showChangeForm'])->name('password.change');
    Route::post('/cambiar-password', [FirstLoginController::class, 'updatePassword'])->name('password.update_initial');

    Route::middleware(['force.change'])->group(function () {

        // Dashboard
        Route::get('/', function () { return view('dashboard'); })->name('dashboard');

        // MÓDULO USUARIOS
        Route::post('/usuarios/{usuario}/notificacion', [UsuarioController::class, 'notificacion'])->name('usuario.notificacion');
        Route::put('/usuarios/{usuario}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');
        Route::put('/usuarios/{usuario}/reactivar', [UsuarioController::class, 'reactivar'])->name('usuario.reactivar');
        Route::resource('usuarios', UsuarioController::class)->names('usuario');

        // MÓDULO TIPOS DE REQUERIMIENTOS
        Route::resource('tiporequerimiento', TipoRequerimientoController::class)
            ->parameters(['tiporequerimiento' => 'tiporequerimiento']);

        // MÓDULO SISTEMAS
        Route::resource('sistema', SistemaController::class)
            ->parameters(['sistema' => 'sistema']);

        // MÓDULO SOLICITANTES
        Route::get('/api/unidades-por-dependencia/{id}', [SolicitanteController::class, 'getUnidades'])->name('api.unidades');
        Route::resource('solicitante', SolicitanteController::class)
            ->parameters(['solicitante' => 'solicitante']);

        // --- MÓDULO OFICIOS (REGISTRO) ---
        // 1. Oficios Principal (CRUD)
        Route::resource('oficio', OficioController::class)
            ->parameters(['oficio' => 'oficio']); // Asegura que la variable en la ruta sea {oficio}

        // 2. Sub-módulo Solicitantes del Oficio
        // Como tu controlador usa session('oficio_id'), las rutas no necesitan pasar el ID en la URL
        Route::get('oficiosolicitante', [OficioSolicitanteController::class, 'index'])->name('oficiosolicitante.index');
        Route::post('oficiosolicitante', [OficioSolicitanteController::class, 'store'])->name('oficiosolicitante.store');
        // Para eliminar si necesitamos el ID del solicitante específico
        Route::delete('oficiosolicitante/{id}', [OficioSolicitanteController::class, 'destroy'])->name('oficiosolicitante.destroy');

    });
});