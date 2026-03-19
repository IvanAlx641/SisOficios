<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP FUNCTION IF EXISTS quitar_acentos;
            CREATE FUNCTION quitar_acentos(texto VARCHAR(4000))
            RETURNS VARCHAR(4000)
            DETERMINISTIC
            BEGIN
                SET texto = REPLACE(texto, "á", "a");
                SET texto = REPLACE(texto, "é", "e");
                SET texto = REPLACE(texto, "í", "i");
                SET texto = REPLACE(texto, "ó", "o");
                SET texto = REPLACE(texto, "ú", "u");
                SET texto = REPLACE(texto, "Á", "A");
                SET texto = REPLACE(texto, "É", "E");
                SET texto = REPLACE(texto, "Í", "I");
                SET texto = REPLACE(texto, "Ó", "O");
                SET texto = REPLACE(texto, "Ú", "U");
                RETURN texto;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS quitar_acentos;');
    }
};
