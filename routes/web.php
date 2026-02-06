<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirstLoginController;
use App\Http\Controllers\TipoRequerimientoController;
use App\Http\Controllers\SolicitanteController;
// Importamos el controlador de Sistemas
use App\Http\Controllers\SistemaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === 1. RUTAS PÚBLICAS (Login y Recuperación) ===
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de "Olvidé mi contraseña"
Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// === 2. RUTAS PROTEGIDAS (Requieren Login) ===
Route::middleware(['auth'])->group(function () {

    // --- RUTAS DE PRIMER INGRESO (Excluidas del middleware force.change) ---
    Route::get('/cambiar-password', [FirstLoginController::class, 'showChangeForm'])->name('password.change');
    Route::post('/cambiar-password', [FirstLoginController::class, 'updatePassword'])->name('password.update_initial');

    // --- GRUPO: SISTEMA PRINCIPAL (Aplica middleware "force.change") ---
    Route::middleware(['force.change'])->group(function () {

        // Dashboard
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');

        // --- MÓDULO USUARIOS ---
        Route::post('/usuarios/{usuario}/notificacion', [UsuarioController::class, 'notificacion'])->name('usuario.notificacion');
        Route::put('/usuarios/{usuario}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');
        Route::put('/usuarios/{usuario}/reactivar', [UsuarioController::class, 'reactivar'])->name('usuario.reactivar');
        Route::resource('usuarios', UsuarioController::class)->names('usuario');

        // --- MÓDULO TIPOS DE REQUERIMIENTOS ---
        Route::resource('tiporequerimiento', TipoRequerimientoController::class)
            ->parameters(['tiporequerimiento' => 'tiporequerimiento']);

        // --- MÓDULO SISTEMAS DE INFORMACIÓN ---
        // Seguimos el patrón de nombres en singular para el recurso
        Route::resource('sistema', SistemaController::class)
            ->parameters(['sistema' => 'sistema']);

        // --- MÓDULO SOLICITANTES ---
        // 1. Ruta AJAX (API interna) para cargar Unidades según la Dependencia seleccionada
        Route::get('/api/unidades-por-dependencia/{id}', [SolicitanteController::class, 'getUnidades'])
            ->name('api.unidades');

        // 2. Rutas CRUD completas
        Route::resource('solicitante', SolicitanteController::class)
            ->parameters(['solicitante' => 'solicitante']);

    });
});