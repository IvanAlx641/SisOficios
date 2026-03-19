<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DependenciasSeeder::class,
            UnidadesAdministrativasSeeder::class,            
            SistemasSeeder::class,
            TiposRequerimientoSeeder::class,
            SolicitantesSeeder::class,
            OficiosSeeder::class,
            UsuariosSeeder::class,
        ]);
    }
}
