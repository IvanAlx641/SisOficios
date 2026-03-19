<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombre' => 'Hugo Sánchez Toleddano',
            'email'=>'hugo.sanchez@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Titular',
            'dependencia_id' => '176',
            'unidad_administrativa_id' => '101535',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);

        User::create([
            'nombre' => 'Ricardo Santamaria Sánchez',
            'email'=>'ricardo.santamaria@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Administrador TI',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);

        User::create([
            'nombre' => 'Araceli Sánchez Garcia',
            'email'=>'araceli.sanchez@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Administrador TI',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);

        User::create([
            'nombre' => 'Ana Karina Garcia Rojas',
            'email'=>'karina.garcia@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Administrador TI',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);

        User::create([
            'nombre' => 'Victor Clementes Santander',
            'email'=>'victor.clementes@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Administrador TI',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);

        User::create([
            'nombre' => 'Nadia Martínez Gómez',
            'email'=>'nadia.martinez@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Capturista',
            'dependencia_id' => '176',
            'unidad_administrativa_id' => '101535',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);

        User::create([
            'nombre' => 'Itzel Amairani Carmona Rios',
            'email'=>'itzel.carmona@secogem.gob.mx',
            'password'=>Hash::make('desa'),
            'email_verified_at'=>now(),
            'rol' => 'Analista',
            'dependencia_id' => '176',
            'unidad_administrativa_id' => '101535',
            'usuario_creacion_id'=>'1',
            'fecha_creacion'=>now(),
        ]);
    }
}
