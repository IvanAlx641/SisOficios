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
        Schema::create('oficios', function (Blueprint $table) {
            $table->id();
            $table->enum('estatus',['Pendiente','Cancelado','Turnado','Concluido', 'Atendido']);
            $table->string('numero_oficio')->unique();
            $table->dateTime('fecha_recepcion');
            $table->unsignedBigInteger('dirigido_id');
            $table->text('descripción_oficio');
            $table->text('url_oficio');
            $table->string('solicitud_conjunta',1)->nullable();
            $table->unsignedBigInteger('area_asignada_id');
            $table->unsignedBigInteger('sistema_id')->nullable();
            $table->unsignedBigInteger('tipo_requerimiento_id')->nullable();
            $table->dateTime('fecha_cancelacion')->nullable();
            $table->dateTime('fecha_turno')->nullable();
            $table->dateTime('fecha_conclusion')->nullable();
            $table->string('soporte_documental')->nullable();
            $table->mediumText('propuesta_respuesta')->nullable();
            $table->string('alcance_otro_oficio',1)->nullable();
            $table->dateTime('fecha_respuesta')->nullable();
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
        Schema::dropIfExists('oficios');
    }
};
