<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasMany; // Descomentar cuando existan Oficio y Detalle

class TipoRequerimiento extends Model
{
    use HasFactory;

    protected $table = 'ctipos_requerimientos';
    
    // Desactivamos timestamps automáticos (usamos fecha_creacion/modificacion manuales)
    public $timestamps = false;

    protected $fillable = [
        'tipo_requerimiento',
        'requerimiento_oficio',
        'requerimiento_actividad',
        'inactivo',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    /* --- RELACIONES DISPONIBLES (User SÍ existe) --- */

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function modificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_modificacion_id');
    }

    /* --- RELACIONES PENDIENTES (Modelos aún no creados) --- */
    
    /*
    public function detalleActividades(): HasMany
    {
        return $this->hasMany(DetalleActividad::class, 'tipo_requerimiento_id');
    }

    public function oficios(): HasMany
    {
        return $this->hasMany(Oficio::class, 'tipo_requerimiento_id');
    }
    */
}