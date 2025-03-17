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
            $table->string('carte_reception')->nullable()->after('electronique_detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->dropColumn('carte_reception');
        });
    }
};
