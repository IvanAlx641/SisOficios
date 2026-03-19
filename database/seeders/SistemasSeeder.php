<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sistema;
use Illuminate\Support\Carbon;

class SistemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sistema::create([
            'nombre_sistema' => 'Sistema de Atención Mexiquense',
            'sigla_sistema' => 'SAM',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);
        Sistema::create([
            'nombre_sistema' => 'Sistema de Registro Estatal de Inspectores',
            'sigla_sistema' => 'REI',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Constancias de No Inhabilitación',
            'sigla_sistema' => 'Constancias',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Empresas y personas físicas objetadas y sancionadas',
            'sigla_sistema' => 'Empresas Objetadas',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Gestión de Servicios Administrativos',
            'sigla_sistema' => 'SIGESA',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Denuncias Éticas',
            'sigla_sistema' => 'SIDE',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema para la Captura del Presupuesto Federal',
            'sigla_sistema' => 'SICAPREF',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'DeclaraNET',
            'sigla_sistema' => 'DeclaraNET',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'BackOffice del DeclaraNET',
            'sigla_sistema' => 'DeclaraNET-BackOffice',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Tableros de Control',
            'sigla_sistema' => 'Tableros',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema Automatizado de Auditorías Estatales y Federales',
            'sigla_sistema' => 'SAAEF',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema Integral de Contraloría Social',
            'sigla_sistema' => 'SICOSO',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Entrega - Recepción',
            'sigla_sistema' => 'SISER-WEB',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'BackOffice del Sistema de Entrega - Recepción',
            'sigla_sistema' => 'SISER-BackOffice',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Trazabilidad del Estado de México',
            'sigla_sistema' => 'SITRAEM',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de Almacén Estatal',
            'sigla_sistema' => 'Almacen Estatal',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'Sistema de los Comités de Ética',
            'sigla_sistema' => 'SICOE',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);

        Sistema::create([
            'nombre_sistema' => 'BackOffice del Sistema de los Comités de Ética',
            'sigla_sistema' => 'SICOE-BackOffice',
            'fecha_creacion' => Carbon::now(),
            'usuario_creacion_id' => 1,
            'fecha_modificacion' => null,
            'usuario_modificacion_id' => null,
        ]);
        
    }
}
