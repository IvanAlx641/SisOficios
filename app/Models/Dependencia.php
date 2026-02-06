<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'cdependencias';
    public $timestamps = false; // No usa created_at/updated_at

    protected $fillable = [
        'nombre_dependencia',
        'orden',
        'inactivo',
    ];

    // Relación: Una dependencia tiene muchas unidades (Se usará después para el AJAX)
    public function unidadesAdministrativas()
    {
        return $this->hasMany(UnidadAdministrativa::class, 'dependencia_id');
    }
}