<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo')->default('caja_general'); // caja_general, caja_chica
            $table->decimal('saldo_inicial', 12, 2)->default(0.00);
            $table->decimal('saldo_actual', 12, 2)->default(0.00);
            $table->string('estado')->default('abierta'); // abierta, cerrada
            $table->timestamps();
        });

        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas')->cascadeOnDelete();
            $table->string('tipo'); // ingreso, egreso
            $table->decimal('monto', 12, 2);
            $table->string('concepto');
            $table->string('categoria')->nullable();
            $table->string('referencia_tipo')->nullable(); // egreso, ingreso, venta, pago_planilla
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->date('fecha');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
        Schema::dropIfExists('cajas');
    }
};
