<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadAdministrativa;

class UnidadesAdministrativasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
$csv = fopen(database_path('seeders/Unidades_SECOGEM.csv'), 'r');
        $header = fgetcsv($csv);
        // Eliminar BOM si existe en la primera clave
        //if (isset($header[0])) {
        //    $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        //}
        
        while (($row = fgetcsv($csv)) !== false) {
            $data = array_combine($header, $row);
            UnidadAdministrativa::create([
                'id' => $data['nci'],
                'dependencia_id' => $data['dependencia_id'],
                'nombre_unidad_administrativa' => $data['nombre_unidad_administrativa'],
                'inactivo' => $data['inactivo'] !== '' ? $data['inactivo'] : null,
                'clave_unica' => $data['clave_unica'],
                'orden' => $data['orden'],
            ]);
        }
        fclose($csv);
    }
}
