<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
// IMPORTAMOS LOS MODELOS NECESARIOS
use App\Models\Oficio;
use App\Models\Actividad;

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

    // --- Relaciones de Restricción de Eliminación ---
    public function oficios(): HasMany
    {
        return $this->hasMany(Oficio::class, 'sistema_id');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'sistema_id');
    }
}