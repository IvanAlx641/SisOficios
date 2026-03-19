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
        Schema::create('detalle_actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actividad_id');
            $table->unsignedBigInteger('tipo_requerimiento_id');
            $table->string('descripcion_actividad',4000);
            $table->enum('estatus',['En proceso','Atendida']);

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
        Schema::dropIfExists('detalle_actividades');
    }
};
