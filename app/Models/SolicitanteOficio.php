<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitanteOficio extends Model
{
    use HasFactory;

    protected $table = 'solicitantes_oficios';
    public $timestamps = false;

    protected $fillable = [
        'oficio_id',
        'solicitante_id',
        'fecha_creacion',
        'usuario_creacion_id',
    ];
}