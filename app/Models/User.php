<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    // --- Configuración de Fechas Personalizadas ---
    // Laravel llenará automáticamente fecha_creacion y fecha_modificacion
    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';

    // Lista exacta de columnas permitidas (según tu DB)
    protected $fillable = [
        'nombre',
        'email',
        'rol',
        'inactivo',                 // 'X' o NULL
        'email_verified_at',
        'password',
        'dependencia_id',
        'unidad_administrativa_id',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'fecha_ultimo_acceso'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
        'fecha_ultimo_acceso' => 'datetime',
        'password' => 'hashed', // Encriptación automática
    ];

    // --- Relaciones de Auditoría ---
    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function modificador()
    {
        return $this->belongsTo(User::class, 'usuario_modificacion_id');
    }
    // --- Relaciones de Clases ---
    public function tiposRequerimientosCreados()
    {
        return $this->hasMany(TipoRequerimiento::class, 'usuario_creacion_id');
    }

    public function tiposRequerimientosModificados()
    {
        return $this->hasMany(TipoRequerimiento::class, 'usuario_modificacion_id');
    }
    /**
     * Relación con Unidad Administrativa
     */
    public function unidadAdministrativa()
    {
        return $this->belongsTo(UnidadAdministrativa::class, 'unidad_administrativa_id');
    }
}