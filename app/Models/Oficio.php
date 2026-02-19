<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oficio extends Model
{
    use HasFactory;

    protected $table = 'oficios';
    public $timestamps = false; // Manejo manual de timestamps

    protected $fillable = [
        'estatus',
        'numero_oficio',
        'fecha_recepcion',
        'dirigido_id',
        'descripción_oficio', // Respetando acento BD
        'url_oficio',
        'solicitud_conjunta',
        'area_asignada_id',
        'sistema_id',
        'tipo_requerimiento_id',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_recepcion' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    // Relaciones
    public function areaDirigido()
    {
        return $this->belongsTo(UnidadAdministrativa::class, 'dirigido_id');
    }

    public function areaAsignada()
    {
        return $this->belongsTo(UnidadAdministrativa::class, 'area_asignada_id');
    }

    public function solicitantes()
    {
        // Relación muchos a muchos a través de 'solicitantes_oficios'
        return $this->belongsToMany(Solicitante::class, 'solicitantes_oficios', 'oficio_id', 'solicitante_id')
                    ->withPivot('id'); // Importante para el delete específico
    }
    // --- NUEVAS RELACIONES FALTANTES ---
    public function sistema()
    {
        return $this->belongsTo(Sistema::class, 'sistema_id');
    }

    public function tipoRequerimiento()
    {
        return $this->belongsTo(TipoRequerimiento::class, 'tipo_requerimiento_id');
    }
}