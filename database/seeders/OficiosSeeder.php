<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Oficio;
use App\Models\SolicitanteOficio;
use Illuminate\Support\Carbon;

class OficiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Oficio::create([
            'estatus' => 'Pendiente',
            'numero_oficio' => '21803000020000L-4890/2025',
            'fecha_recepcion' => Carbon::now(),
            'dirigido_id' => 101535,
            'solicitud_conjunta' => false,
            'descripción_oficio' => 'Primer oficio de prueba',
            'url_oficio' => 'http://portal.secogem.gob.mx',
            'area_asignada_id' => 101536,
            'sistema_id' => 1,
            'tipo_requerimiento_id' => 1,
            'fecha_cancelacion' => null,
            'fecha_turno' => null,
            'fecha_conclusion' => null,
            'soporte_documental' => null,
            'propuesta_respuesta' => null,
            'alcance_otro_oficio' => null,
            'fecha_respuesta' => null,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        SolicitanteOficio::create([
            'oficio_id' => 1,
            'solicitante_id' => 1,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
        ]);

    }
}
