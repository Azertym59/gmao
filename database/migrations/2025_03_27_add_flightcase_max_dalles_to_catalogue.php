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
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->integer('flightcase_max_dalles')->nullable()->after('disposition_modules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->dropColumn('flightcase_max_dalles');
        });
    }
};
