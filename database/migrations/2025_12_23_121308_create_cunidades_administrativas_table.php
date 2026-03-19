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
        Schema::create('cunidades_administrativas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dependencia_id')->nullable();
            $table->string('nombre_unidad_administrativa');
            $table->string('clave_unica')->nullable();
            $table->string('orden')->nullable();
            $table->string('inactivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cunidades_administrativas');
    }
};
