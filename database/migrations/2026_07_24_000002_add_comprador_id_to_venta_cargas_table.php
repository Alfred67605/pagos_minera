<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_cargas', function (Blueprint $table) {
            $table->foreignId('comprador_id')->nullable()->after('bocamina_id')->constrained('compradores')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('venta_cargas', function (Blueprint $table) {
            $table->dropForeign(['comprador_id']);
            $table->dropColumn('comprador_id');
        });
    }
};
