<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponsableOficio extends Model
{
    use HasFactory;

    protected $table = 'responsables_oficios';
    public $timestamps = false;

    protected $fillable = [
        'oficio_id',
        'responsable_id',
        'genera_respuesta',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    public function oficio()
    {
        return $this->belongsTo(Oficio::class, 'oficio_id');
    }

    // Aquí cambiamos a User::class para que traiga la info del empleado asignado
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    // --- NUEVA RELACIÓN PARA EL TIMELINE ---
    public function seguimientos()
    {
        // Un responsable en un oficio puede registrar muchos avances/seguimientos
        return $this->hasMany(Seguimiento::class, 'responsable_oficio_id')->orderBy('fecha_seguimiento', 'desc');
    }
}
