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
        Schema::table('reparations', function (Blueprint $table) {
            // Vérifier si la colonne description n'existe pas
            if (!Schema::hasColumn('reparations', 'description')) {
                $table->text('description')->default('')->after('remarques');
            }
            
            // Vérifier si la colonne actions n'existe pas
            if (!Schema::hasColumn('reparations', 'actions')) {
                $table->text('actions')->default('')->after('description');
            }
            
            // Vérifier si la colonne pieces_remplacees n'existe pas
            if (!Schema::hasColumn('reparations', 'pieces_remplacees')) {
                $table->text('pieces_remplacees')->nullable()->after('actions');
            }
            
            // Vérifier si la colonne resultat n'existe pas
            if (!Schema::hasColumn('reparations', 'resultat')) {
                $table->string('resultat')->nullable()->after('pieces_remplacees');
            }
            
            // Modifier les colonnes existantes pour s'assurer qu'elles ont des valeurs par défaut
            $table->integer('nb_leds_remplacees')->default(0)->change();
            $table->integer('nb_ic_remplaces')->default(0)->change();
            $table->integer('nb_masques_remplaces')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reparations', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées si nécessaire
            if (Schema::hasColumn('reparations', 'description')) {
                $table->dropColumn('description');
            }
            
            if (Schema::hasColumn('reparations', 'actions')) {
                $table->dropColumn('actions');
            }
            
            if (Schema::hasColumn('reparations', 'pieces_remplacees')) {
                $table->dropColumn('pieces_remplacees');
            }
            
            if (Schema::hasColumn('reparations', 'resultat')) {
                $table->dropColumn('resultat');
            }
        });
    }
};
