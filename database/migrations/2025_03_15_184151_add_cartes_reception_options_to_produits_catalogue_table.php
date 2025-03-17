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
            // Vérifier si la colonne existe déjà
            if (!Schema::hasColumn('produits_catalogue', 'cartes_reception_disponibles')) {
                $table->json('cartes_reception_disponibles')->nullable()->after('carte_reception');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->dropColumn('cartes_reception_disponibles');
        });
    }
};
