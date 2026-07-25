<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producciones_mineras', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('bocamina_id')->constrained('bocaminas')->cascadeOnDelete();
            $table->string('veta_sector')->nullable();
            $table->string('tipo_mineral')->default('Complejo (Zn-Pb-Ag)');
            $table->decimal('cargas_extraidas', 10, 2);
            $table->decimal('toneladas_estimadas', 10, 2);
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producciones_mineras');
    }
};
