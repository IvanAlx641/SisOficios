<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadAdministrativa extends Model
{
    use HasFactory;

    protected $table = 'cunidades_administrativas';
    public $timestamps = false;

    protected $fillable = [
        'dependencia_id',
        'nombre_unidad_administrativa',
        'clave_unica',
        'orden',
        'inactivo',
    ];

    /* --- RELACIONES ACTIVAS --- */

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function solicitantes(): HasMany
    {
        return $this->hasMany(Solicitante::class, 'unidad_administrativa_id');
    }

    /* --- SCOPES (Opcional, útil para selects simples) --- */
    public function scopeActivas($query)
    {
        return $query->whereNull('inactivo')->orderBy('orden');
    }
}