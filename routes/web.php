<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirstLoginController;

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
    // Estas rutas deben ser accesibles aunque el usuario no esté verificado
    Route::get('/cambiar-password', [FirstLoginController::class, 'showChangeForm'])->name('password.change');
    Route::post('/cambiar-password', [FirstLoginController::class, 'updatePassword'])->name('password.update_initial');

    // --- GRUPO: SISTEMA PRINCIPAL (Aplica middleware "force.change") ---
    // Aquí metemos todo lo que requiere que el usuario ya tenga su contraseña configurada
    Route::middleware(['force.change'])->group(function () {
        
        // Dashboard
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');

        // Módulo Usuarios
        Route::post('/usuarios/{usuario}/notificacion', [UsuarioController::class, 'notificacion'])->name('usuario.notificacion');
        Route::put('/usuarios/{usuario}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');
        Route::put('/usuarios/{usuario}/reactivar', [UsuarioController::class, 'reactivar'])->name('usuario.reactivar');
        Route::resource('usuarios', UsuarioController::class)->names('usuario');

    });

});