<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actividad extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'actividades';

    protected $fillable = [
        'fecha_actividad',
        'responsable_id',
        'sistema_id',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_actividad' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    // --- RELACIONES ---
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Sistema::class, 'sistema_id');
    }

    public function detalleActividades(): HasMany
    {
        return $this->hasMany(DetalleActividad::class, 'actividad_id');
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