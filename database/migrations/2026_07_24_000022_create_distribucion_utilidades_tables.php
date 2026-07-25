<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribucion_utilidades', function (Blueprint $table) {
            $table->id();
            $table->string('numero_distribucion')->unique();
            $table->string('periodo'); // ej: Q1 2026, Julio 2026
            $table->date('fecha');
            $table->decimal('utilidad_bruta_total', 14, 2);
            $table->decimal('deducciones_reserva', 14, 2)->default(0.00);
            $table->decimal('utilidad_neta_distribuir', 14, 2);
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('detalle_utilidad_socios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribucion_utilidad_id')->constrained('distribucion_utilidades')->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->decimal('porcentaje_participacion', 5, 2);
            $table->decimal('monto_utilidad', 14, 2);
            $table->string('estado')->default('pagado'); // pendiente, pagado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_utilidad_socios');
        Schema::dropIfExists('distribucion_utilidades');
    }
};
