<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RespuestaOficio extends Model
{
    use HasFactory;

    protected $table = 'respuestas_oficios';
    public $timestamps = false; // Manejas las fechas manualmente

    protected $fillable = [
        'oficio_id',
        'fecha_respuesta',
        'numero_oficio_respuesta',
        'dirigido_a_id', // Agregado desde tu imagen de BD
        'firmado_por_id',
        'url_oficio_respuesta',
        'descripción_respuesta_oficio',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_respuesta' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    // Relación al Oficio origen
    public function oficio()
    {
        return $this->belongsTo(Oficio::class, 'oficio_id');
    }

    // Quién firma la respuesta
    public function firmadoPor()
    {
        return $this->belongsTo(User::class, 'firmado_por_id');
    }

    // A quién va dirigida la respuesta
    public function dirigidoA()
    {
        return $this->belongsTo(Solicitante::class, 'dirigido_a_id');
    }
    public function respuestasOficios()
    {
        // Asegúrate de que el nombre de la clase y la llave foránea sean correctos
        return $this->hasMany(RespuestaOficio::class, 'oficio_id');
    }
    
}
