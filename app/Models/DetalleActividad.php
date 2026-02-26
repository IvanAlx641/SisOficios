<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleActividad extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'detalle_actividades';

    protected $fillable = [
        'actividad_id',
        'tipo_requerimiento_id',
        'descripcion_actividad',
        'estatus',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    // --- RELACIONES ---
    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function tipoRequerimiento(): BelongsTo
    {
        return $this->belongsTo(TipoRequerimiento::class, 'tipo_requerimiento_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function modificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_modificacion_id');
    }
}