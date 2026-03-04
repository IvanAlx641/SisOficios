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
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\BuscadorController;

// --- IMPORTAMOS LOS NUEVOS CONTROLADORES DE ACTIVIDADES ---
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\DetalleActividadController;

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
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');

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
        Route::post('/oficiosolicitantes/finalizar', [OficioSolicitanteController::class, 'finalizar'])->name('oficiosolicitante.finalizar');
        // --- MÓDULO OFICIOS (REGISTRO) ---
        // 1. Oficios Principal (CRUD)
        Route::resource('oficio', OficioController::class)
            ->parameters(['oficio' => 'oficio']);

        // 2. Sub-módulo Solicitantes del Oficio
        Route::get('oficiosolicitante', [OficioSolicitanteController::class, 'index'])->name('oficiosolicitante.index');
        Route::post('oficiosolicitante', [OficioSolicitanteController::class, 'store'])->name('oficiosolicitante.store');
        Route::delete('oficiosolicitante/{id}', [OficioSolicitanteController::class, 'destroy'])->name('oficiosolicitante.destroy');

        // --- MÓDULO TURNOS ---
        Route::resource('turno', TurnoController::class)
            ->parameters(['turno' => 'turno'])
            ->names('turno')
            ->only(['index', 'edit', 'update']);

        // --- SUB-MÓDULO RESPONSABLES ---
        Route::resource('responsable', ResponsableController::class)
            ->parameters(['responsable' => 'responsable'])
            ->names('responsable');

        // --- MÓDULO SEGUIMIENTO (TIMELINE Y CONCLUSIÓN) ---
        // 1. Index principal
        Route::get('seguimiento', [SeguimientoController::class, 'index'])->name('seguimiento.index');

        // 2. Guardar nuevo avance en el timeline (Recibe el ID del responsable_oficio)
        Route::post('seguimiento/{responsableOficioId}/avance', [SeguimientoController::class, 'storeAvance'])->name('seguimiento.avance.store');

        // 3. Concluir el Oficio
        Route::post('seguimiento/{oficio}/concluir', [SeguimientoController::class, 'concluir'])->name('seguimiento.concluir');
        
        // --- MÓDULO RESPUESTAS ---
        // 1. Index principal
        Route::get('/respuestas', [RespuestaController::class, 'index'])->name('respuestas.index');

        // 2. Guardar nueva respuesta (Recibe el ID del Oficio para asociarlo)
        Route::post('/respuestas/{oficio}', [RespuestaController::class, 'store'])->name('respuestas.store');

        // 3. Eliminar respuesta
        Route::delete('/respuestas/{respuesta}', [RespuestaController::class, 'destroy'])->name('respuestas.destroy');

        Route::get('/detallerespuestas/{oficio}', [RespuestaController::class, 'show'])->name('detallerespuestas.index');
        Route::put('/respuestas/{respuesta}', [RespuestaController::class, 'update'])->name('respuestas.update');

        // --- MÓDULO BUSCADOR ---
        Route::get('/buscador', [BuscadorController::class, 'index'])->name('buscador.index');
        Route::get('/buscador/{id}', [BuscadorController::class, 'show'])->name('buscador.show');

        // ==========================================
        // --- NUEVO MÓDULO ACTIVIDADES ---
        // ==========================================
        Route::resource('actividad', ActividadController::class)
            ->parameters(['actividad' => 'actividad']);

        Route::resource('detalleactividad', DetalleActividadController::class)
            ->parameters(['detalleactividad' => 'detalleactividad'])
            ->except(['create', 'show']); // Excluimos las que no usamos para no hacer ruido
    });
});