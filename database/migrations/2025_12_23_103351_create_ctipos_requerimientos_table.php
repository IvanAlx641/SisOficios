<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ctipos_requerimientos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_requerimiento');
            $table->string('requerimiento_oficio', 1)->nullable();
            $table->string('requerimiento_actividad', 1)->nullable();
            $table->string('inactivo', 1)->nullable();            
            $table->timestamp('fecha_creacion')->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->timestamp('fecha_modificacion')->nullable();
            $table->unsignedBigInteger('usuario_modificacion_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctipos_requerimientos');
    }
};
