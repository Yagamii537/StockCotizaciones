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
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade'); // Relación con clientes
            $table->date('fecha'); // Fecha de la cotización
            $table->decimal('total', 10, 2)->default(0); // Total de la cotización
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('estado')->default(1); // Estado numérico (1 a 5)
            $table->text('observaciones')->nullable(); // Observaciones opcionales
            $table->decimal('descuento', 10, 2)->nullable(); // Descuento opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacions');
    }
};
