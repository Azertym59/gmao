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
        Schema::table('produits', function (Blueprint $table) {
            // Nous allons d'abord copier les valeurs de type_carte_reception vers carte_reception si elles existent
            DB::statement("UPDATE produits SET carte_reception = CASE 
                WHEN carte_reception IS NULL OR carte_reception = '' THEN type_carte_reception 
                WHEN type_carte_reception IS NOT NULL AND type_carte_reception != '' THEN CONCAT(carte_reception, ' (', type_carte_reception, ')') 
                ELSE carte_reception 
            END 
            WHERE type_carte_reception IS NOT NULL AND type_carte_reception != ''");
            
            // Ensuite supprimer la colonne
            $table->dropColumn('type_carte_reception');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->string('type_carte_reception')->nullable()->after('carte_reception');
        });
    }
};
