<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dependencia;

class DependenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dependencia::create([
            'id' => 176,
            'nombre_dependencia' => 'SECRETARÍA DE LA CONTRALORÍA',
            'orden' => '1',
            'inactivo' => null,
        ]);        
    }
}
