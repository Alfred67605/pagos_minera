<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_prestamo')->unique();
            $table->foreignId('socio_id')->nullable()->constrained('socios')->nullOnDelete();
            $table->foreignId('trabajador_id')->nullable()->constrained('trabajadores')->nullOnDelete();
            $table->decimal('monto_total', 12, 2);
            $table->decimal('monto_cuota', 12, 2);
            $table->integer('total_cuotas');
            $table->integer('cuotas_pagadas')->default(0);
            $table->decimal('saldo_pendiente', 12, 2);
            $table->date('fecha_otorgamiento');
            $table->string('estado')->default('activo'); // activo, completado, anulado
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cuotas_prestamo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->cascadeOnDelete();
            $table->integer('numero_cuota');
            $table->decimal('monto_cuota', 12, 2);
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, pagado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas_prestamo');
        Schema::dropIfExists('prestamos');
    }
};
