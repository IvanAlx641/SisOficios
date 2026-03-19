<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solicitante;
use Illuminate\Support\Carbon;

class SolicitantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Solicitante::create([
            'nombre' => 'LIC. HILDA SALAZAR GIL',
            'dependencia_id' => '176',
            'unidad_administrativa_id' => '9710',
            'cargo' => 'SECRETARIA DE LA CONTRALORÍA',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Solicitante::create([
            'nombre' => 'LIC. MIGUEL CORTES ISLAS',
            'dependencia_id' => '176',
            'unidad_administrativa_id' => '101552',
            'cargo' => 'SUBSECRETARIO DE RESPONSABILIDADES ADMINISTRATIVAS',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Solicitante::create([
            'nombre' => 'LIC. ARTURO RAMÍREZ SALAZAR',
            'dependencia_id' => '176',
            'unidad_administrativa_id' => '101554',
            'cargo' => 'ENCARGADO DE DESPACHO DE LA DIRECCIÓN DE INVESTIGACIÓN',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

    }
}
