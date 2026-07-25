<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_contables', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('tipo'); // activo, pasivo, patrimonio, ingreso, gasto
            $table->integer('nivel')->default(1);
            $table->timestamps();
        });

        Schema::create('asientos_contables', function (Blueprint $table) {
            $table->id();
            $table->string('numero_asiento')->unique();
            $table->date('fecha');
            $table->text('glosa');
            $table->decimal('debe_total', 14, 2);
            $table->decimal('haber_total', 14, 2);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('detalle_asientos_contables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asiento_contable_id')->constrained('asientos_contables')->cascadeOnDelete();
            $table->foreignId('cuenta_contable_id')->constrained('cuentas_contables')->cascadeOnDelete();
            $table->decimal('debe', 14, 2)->default(0.00);
            $table->decimal('haber', 14, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_asientos_contables');
        Schema::dropIfExists('asientos_contables');
        Schema::dropIfExists('cuentas_contables');
    }
};
