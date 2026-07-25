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
        Schema::table('anticipos', function (Blueprint $table) {
            $table->string('tipo_receptor')->default('trabajador')->after('id'); // trabajador, socio
            $table->foreignId('trabajador_id')->nullable()->change();
            $table->foreignId('socio_id')->nullable()->after('trabajador_id')->constrained('socios')->cascadeOnDelete();
            $table->string('motivo')->nullable()->after('monto');
            $table->foreignId('user_id')->nullable()->after('pagado')->constrained('users')->nullOnDelete();
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->string('tipo_receptor')->default('trabajador')->after('id'); // trabajador, socio
            $table->foreignId('trabajador_id')->nullable()->change();
            $table->foreignId('socio_id')->nullable()->after('trabajador_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->after('observacion')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['socio_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['tipo_receptor', 'socio_id', 'user_id']);
        });

        Schema::table('anticipos', function (Blueprint $table) {
            $table->dropForeign(['socio_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['tipo_receptor', 'socio_id', 'motivo', 'user_id']);
        });
    }
};
