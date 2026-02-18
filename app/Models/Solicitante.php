<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Solicitante extends Model
{
    use HasFactory;

    protected $table = 'csolicitantes';
    public $timestamps = false; // Usamos fechas manuales

    protected $fillable = [
        'nombre',
        'dependencia_id',
        'unidad_administrativa_id',
        'cargo',
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

    // --- RELACIONES ACTIVAS (Modelos que YA existen) ---
    public function oficios(): BelongsToMany
    {
        // Relación muchos a muchos con la tabla pivote
        return $this->belongsToMany(Oficio::class, 'solicitantes_oficios', 'solicitante_id', 'oficio_id');
    }

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function unidadAdministrativa(): BelongsTo
    {
        return $this->belongsTo(UnidadAdministrativa::class, 'unidad_administrativa_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function modificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_modificacion_id');
    }

    // --- RELACIONES PENDIENTES (Modelos que AÚN NO existen) ---
    // Comentadas para que no den error

    /*
    public function solicitantesOficios(): HasMany
    {
        return $this->hasMany(SolicitanteOficio::class, 'solicitante_id');
    }
    */

    // Helper para listas (útil para cuando usemos Solicitante en el módulo de Oficios)
    public function scopeListaSolicitantes($query) : array
    {
        return $query->selectRaw("id, CONCAT(nombre, ' - ', cargo) AS nombre_cargo")
            ->whereNull('inactivo')
            ->pluck('nombre_cargo', 'id')
            ->prepend('Selecciona un solicitante', '')
            ->toArray();
    }
    
}