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
            $table->string('disposition_modules')->nullable()->after('hub')->comment('Configuration des modules (ex: 2x2, 3x3)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->dropColumn('disposition_modules');
        });
    }
};
