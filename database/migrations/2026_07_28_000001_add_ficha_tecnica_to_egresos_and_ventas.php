<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('egresos', function (Blueprint $table) {
            $table->string('presentacion')->default('saco')->after('concepto'); // volqueta, saco, concentrado, bruto
            $table->foreignId('bocamina_id')->nullable()->after('presentacion')->constrained('bocaminas')->nullOnDelete();
            $table->decimal('peso_bruto', 10, 2)->nullable()->after('bocamina_id');
            $table->decimal('tara', 10, 2)->nullable()->after('peso_bruto');
            $table->decimal('peso_neto', 10, 2)->nullable()->after('tara');
            $table->string('ley_mineral')->nullable()->after('peso_neto');
        });

        Schema::table('venta_cargas', function (Blueprint $table) {
            $table->string('presentacion')->default('saco')->after('tipo_mineral'); // volqueta, saco, concentrado, bruto
            $table->decimal('peso_bruto', 10, 2)->nullable()->after('cantidad');
            $table->decimal('tara', 10, 2)->nullable()->after('peso_bruto');
            $table->string('ley_mineral')->nullable()->after('peso_neto');
            $table->foreignId('caja_id')->nullable()->after('observaciones')->constrained('cajas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('egresos', function (Blueprint $table) {
            $table->dropForeign(['bocamina_id']);
            $table->dropColumn(['presentacion', 'bocamina_id', 'peso_bruto', 'tara', 'peso_neto', 'ley_mineral']);
        });

        Schema::table('venta_cargas', function (Blueprint $table) {
            $table->dropForeign(['caja_id']);
            $table->dropColumn(['presentacion', 'peso_bruto', 'tara', 'ley_mineral', 'caja_id']);
        });
    }
};
