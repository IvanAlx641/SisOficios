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
        Schema::create('seguimientos_oficios', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('responsable_oficio_id');
            $table->timestamp('fecha_seguimiento');
            $table->enum('estatus',['Pendiente','En Desarrollo','En validación','Publicado']);
            $table->string('observaciones',2000);
            
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
        Schema::dropIfExists('seguimientos_oficios');
    }
};
