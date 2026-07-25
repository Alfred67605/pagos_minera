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
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->string('cargo')->default('trabajador_bocamina')->after('nombre'); // trabajador_bocamina, sereno, chofer, personal_admin
            $table->date('fecha_ingreso')->nullable()->after('cargo');
            $table->string('modalidad_pago')->default('por_produccion')->after('fecha_ingreso'); // por_produccion, sueldo_fijo
            $table->decimal('sueldo_base', 10, 2)->default(0)->after('modalidad_pago');
            $table->text('observaciones')->nullable()->after('sueldo_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropColumn(['cargo', 'fecha_ingreso', 'modalidad_pago', 'sueldo_base', 'observaciones']);
        });
    }
};
