<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoRequerimiento;
use Illuminate\Support\Carbon;

class TiposRequerimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoRequerimiento::create([
            'tipo_requerimiento' => 'Cambios menores en el funcionamiento de un sistema',
            'requerimiento_oficio' => true,
            'requerimiento_actividad' => true,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);
        TipoRequerimiento::create([
            'tipo_requerimiento' => 'Reingeniería de sistemas',
            'requerimiento_oficio' => true,
            'requerimiento_actividad' => true,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        TipoRequerimiento::create([
            'tipo_requerimiento' => 'Cambios a datos',
            'requerimiento_oficio' => true,
            'requerimiento_actividad' => true,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        TipoRequerimiento::create([
            'tipo_requerimiento' => 'Solicitud de información',
            'requerimiento_oficio' => true,
            'requerimiento_actividad' => true,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        TipoRequerimiento::create([
            'tipo_requerimiento' => 'Actualización del contenido en el Portal Institucional',
            'requerimiento_oficio' => true,
            'requerimiento_actividad' => true,
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);
    }
}
