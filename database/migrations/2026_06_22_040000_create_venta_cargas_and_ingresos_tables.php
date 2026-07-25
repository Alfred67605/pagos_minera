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
        Schema::create('venta_cargas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_venta')->unique();
            $table->date('fecha');
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('bocamina_id')->constrained('bocaminas')->cascadeOnDelete();
            $table->string('tipo_mineral');
            $table->integer('cantidad')->nullable();
            $table->decimal('peso_neto', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('total_vendido', 10, 2);
            $table->string('comprador');
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('concepto');
            $table->decimal('monto', 10, 2);
            $table->string('origen')->default('venta_carga'); // venta_carga, cuota_socio, otro
            $table->foreignId('venta_carga_id')->nullable()->constrained('venta_cargas')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
        Schema::dropIfExists('venta_cargas');
    }
};
