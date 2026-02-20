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
        'descripción_oficio', 
        'url_oficio',
        'solicitud_conjunta',
        'area_asignada_id',
        'sistema_id',
        'tipo_requerimiento_id',
        
        // --- CAMPOS DE CONCLUSIÓN QUE FALTABAN ---
        'fecha_conclusion',
        'soporte_documental',
        'propuesta_respuesta',
        'alcance_otro_oficio',
        
        // --- AUDITORÍA ---
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_recepcion' => 'datetime',
        'fecha_conclusion' => 'datetime', // Importante para que la vista lo formatee bien
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

    public function responsablesOficios()
    {
        // Trae los registros de la tabla pivote, y de ahí podemos sacar los seguimientos
        return $this->hasMany(ResponsableOficio::class, 'oficio_id');
    }

    public function oficiosVinculados()
    {
        return $this->belongsToMany(Oficio::class, 'oficios_vinculados', 'oficio_id', 'oficio_vinculado_id');
    }
}