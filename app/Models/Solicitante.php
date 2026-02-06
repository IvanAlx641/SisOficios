<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla en tu imagen
    protected $table = 'csolicitantes'; 

    // Configuración de fechas personalizadas (Auditoría)
    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';

    // Campos permitidos para llenado masivo
    protected $fillable = [
        'nombre',
        'cargo',
        'dependencia_id',
        'unidad_administrativa_id',
        'inactivo',                // 'X' o NULL
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];

    // Formateo de fechas
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    // --- Relaciones para la Tabla ---
    
    // Para mostrar el nombre de la Unidad en la tabla
    public function unidad()
    {
        return $this->belongsTo(UnidadAdministrativa::class, 'unidad_administrativa_id');
    }

    // Para mostrar quién lo registró
    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }
}