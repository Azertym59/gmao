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
        Schema::table('dalles', function (Blueprint $table) {
            $table->string('numero_dalle')->nullable()->after('reference_dalle')->comment('Numéro de dalle d\'usine ou personnalisé par le client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dalles', function (Blueprint $table) {
            $table->dropColumn('numero_dalle');
        });
    }
};
