<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('cedula', 10)->unique(); // Cédula ecuatoriana
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->text('direccion')->nullable(); // Campo opcional
            $table->string('telefono', 15)->nullable(); // Campo opcional
            $table->string('email', 255)->nullable()->unique(); // Email único
            $table->timestamp('fecha_registro')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
