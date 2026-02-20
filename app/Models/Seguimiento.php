<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguimiento extends Model
{
    use HasFactory;

    protected $table = 'seguimientos_oficios'; 
    public $timestamps = false;
    

    protected $fillable = [
        'responsable_oficio_id',
        'fecha_seguimiento',
        'estatus', // 'Pendiente','En Desarrollo','En validación','Publicado'
        'observaciones',
        'fecha_creacion',
        'usuario_creacion_id',
        'fecha_modificacion',
        'usuario_modificacion_id',
    ];

    protected $casts = [
        'fecha_seguimiento' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    // Relación inversa: Este seguimiento pertenece a un "Responsable-Oficio"
    public function responsableOficio()
    {
        return $this->belongsTo(ResponsableOficio::class, 'responsable_oficio_id');
    }

    // Para saber qué usuario registró este avance (Auditoría)
    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }
}