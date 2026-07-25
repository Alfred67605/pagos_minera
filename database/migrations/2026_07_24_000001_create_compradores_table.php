<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compradores', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('nit_ci')->nullable();
            $table->string('contacto_nombre')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->text('notas')->nullable();
            $table->string('estado')->default('activo'); // activo, inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compradores');
    }
};
