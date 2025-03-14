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
        Schema::table('diagnostics', function (Blueprint $table) {
            // Vérifier si la colonne description n'existe pas
            if (!Schema::hasColumn('diagnostics', 'description')) {
                $table->text('description')->default('')->after('remarques');
            }
            
            // Vérifier si la colonne conclusion n'existe pas
            if (!Schema::hasColumn('diagnostics', 'conclusion')) {
                $table->text('conclusion')->default('')->after('description');
            }
            
            // Vérifier si la colonne composant_defectueux n'existe pas
            if (!Schema::hasColumn('diagnostics', 'composant_defectueux')) {
                $table->string('composant_defectueux')->nullable()->after('conclusion');
            }
            
            // Modifier les colonnes existantes pour s'assurer qu'elles ont des valeurs par défaut
            $table->integer('nb_leds_hs')->default(0)->change();
            $table->integer('nb_ic_hs')->default(0)->change();
            $table->integer('nb_masques_hs')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnostics', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées si nécessaire
            if (Schema::hasColumn('diagnostics', 'description')) {
                $table->dropColumn('description');
            }
            
            if (Schema::hasColumn('diagnostics', 'conclusion')) {
                $table->dropColumn('conclusion');
            }
            
            if (Schema::hasColumn('diagnostics', 'composant_defectueux')) {
                $table->dropColumn('composant_defectueux');
            }
        });
    }
};
