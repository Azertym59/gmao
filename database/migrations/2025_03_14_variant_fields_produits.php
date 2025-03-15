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
            // Vérifier si les colonnes existent déjà avant de les ajouter
            if (!Schema::hasColumn('produits', 'carte_reception')) {
                $table->string('carte_reception')->nullable()->after('electronique_detail');
            }
            if (!Schema::hasColumn('produits', 'hub')) {
                $table->string('hub')->nullable()->after('carte_reception');
            }
            if (!Schema::hasColumn('produits', 'bain_couleur')) {
                $table->string('bain_couleur')->nullable()->after('hub');
            }
            if (!Schema::hasColumn('produits', 'variante_id')) {
                $table->unsignedBigInteger('variante_id')->nullable()->after('bain_couleur');
            }
            if (!Schema::hasColumn('produits', 'is_variante')) {
                $table->boolean('is_variante')->default(false)->after('variante_id');
            }
            if (!Schema::hasColumn('produits', 'variante_nom')) {
                $table->string('variante_nom')->nullable()->after('is_variante');
            }
            
            // Ajouter une clé étrangère pour les variantes si elle n'existe pas déjà
            if (!Schema::hasColumn('produits', 'variante_id')) {
                $table->foreign('variante_id')->references('id')->on('produits')->onDelete('cascade');
            }
        });
        
        // Ajouter les colonnes à la table produits_catalogue
        Schema::table('produits_catalogue', function (Blueprint $table) {
            if (!Schema::hasColumn('produits_catalogue', 'cartes_reception_disponibles')) {
                $table->json('cartes_reception_disponibles')->nullable()->after('description');
            }
            if (!Schema::hasColumn('produits_catalogue', 'hubs_disponibles')) {
                $table->json('hubs_disponibles')->nullable()->after('cartes_reception_disponibles');
            }
            if (!Schema::hasColumn('produits_catalogue', 'bains_couleur_disponibles')) {
                $table->json('bains_couleur_disponibles')->nullable()->after('hubs_disponibles');
            }
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