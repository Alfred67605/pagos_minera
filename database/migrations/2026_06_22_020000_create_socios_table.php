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
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('ci')->unique();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->foreignId('bocamina_id')->nullable()->constrained('bocaminas')->nullOnDelete();
            $table->string('estado')->default('activo'); // activo, inactivo
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
