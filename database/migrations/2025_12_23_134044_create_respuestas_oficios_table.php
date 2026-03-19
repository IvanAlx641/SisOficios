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
        Schema::create('respuestas_oficios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('oficio_id');
            $table->timestamp('fecha_respuesta');
            $table->string('numero_oficio_respuesta');
            $table->unsignedBigInteger('dirigido_a_id');
            $table->unsignedBigInteger('firmado_por_id');
            $table->string('url_oficio_respuesta');
            $table->string('descripción_respuesta_oficio',4000);

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
        Schema::dropIfExists('respuestas_oficios');
    }
};
