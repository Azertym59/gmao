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
        // Ajouter les colonnes à la table produits
        Schema::table('produits', function (Blueprint $table) {
            $table->string('carte_reception')->nullable()->after('electronique_detail');
            $table->string('hub')->nullable()->after('carte_reception');
            $table->string('bain_couleur')->nullable()->after('hub');
            $table->unsignedBigInteger('variante_id')->nullable()->after('bain_couleur');
            $table->boolean('is_variante')->default(false)->after('variante_id');
            $table->string('variante_nom')->nullable()->after('is_variante');
            
            // Ajouter une clé étrangère pour les variantes
            $table->foreign('variante_id')->references('id')->on('produits')->onDelete('cascade');
        });
        
        // Ajouter les colonnes à la table produits_catalogue
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->json('cartes_reception_disponibles')->nullable()->after('description');
            $table->json('hubs_disponibles')->nullable()->after('cartes_reception_disponibles');
            $table->json('bains_couleur_disponibles')->nullable()->after('hubs_disponibles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les colonnes de la table produits
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign(['variante_id']);
            $table->dropColumn([
                'carte_reception',
                'hub',
                'bain_couleur',
                'variante_id',
                'is_variante',
                'variante_nom'
            ]);
        });
        
        // Supprimer les colonnes de la table produits_catalogue
        Schema::table('produits_catalogue', function (Blueprint $table) {
            $table->dropColumn([
                'cartes_reception_disponibles',
                'hubs_disponibles',
                'bains_couleur_disponibles'
            ]);
        });
    }
};