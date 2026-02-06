<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sistema extends Model
{
    use HasFactory;

    protected $table = 'csistemas';

    // --- Configuración de Fechas Personalizadas ---
    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'nombre_sistema',
        'sigla_sistema',
        'inactivo',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
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

    // --- Relaciones de Negocio ---
}