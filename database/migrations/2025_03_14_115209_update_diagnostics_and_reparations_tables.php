<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour la table des diagnostics
        if (Schema::hasTable('diagnostics')) {
            Schema::table('diagnostics', function (Blueprint $table) {
                // Ajouter les colonnes manquantes si elles n'existent pas
                if (!Schema::hasColumn('diagnostics', 'description')) {
                    $table->text('description')->nullable()->after('remarques');
                }
                
                if (!Schema::hasColumn('diagnostics', 'conclusion')) {
                    $table->text('conclusion')->nullable()->after('description');
                }
                
                if (!Schema::hasColumn('diagnostics', 'composant_defectueux')) {
                    $table->string('composant_defectueux')->nullable()->after('conclusion');
                }
            });
            
            // Mettre à jour les enregistrements existants pour éviter les valeurs NULL
            DB::statement("UPDATE diagnostics SET description = '' WHERE description IS NULL");
            DB::statement("UPDATE diagnostics SET conclusion = '' WHERE conclusion IS NULL");
            DB::statement("UPDATE diagnostics SET nb_leds_hs = 0 WHERE nb_leds_hs IS NULL");
            DB::statement("UPDATE diagnostics SET nb_ic_hs = 0 WHERE nb_ic_hs IS NULL");
            DB::statement("UPDATE diagnostics SET nb_masques_hs = 0 WHERE nb_masques_hs IS NULL");
        }
        
        // Mettre à jour la table des réparations
        if (Schema::hasTable('reparations')) {
            Schema::table('reparations', function (Blueprint $table) {
                // Ajouter les colonnes manquantes si elles n'existent pas
                if (!Schema::hasColumn('reparations', 'description')) {
                    $table->text('description')->nullable()->after('remarques');
                }
                
                if (!Schema::hasColumn('reparations', 'actions')) {
                    $table->text('actions')->nullable()->after('description');
                }
                
                if (!Schema::hasColumn('reparations', 'pieces_remplacees')) {
                    $table->text('pieces_remplacees')->nullable()->after('actions');
                }
                
                if (!Schema::hasColumn('reparations', 'resultat')) {
                    $table->string('resultat')->nullable()->after('pieces_remplacees');
                }
            });
            
            // Mettre à jour les enregistrements existants pour éviter les valeurs NULL
            DB::statement("UPDATE reparations SET description = '' WHERE description IS NULL");
            DB::statement("UPDATE reparations SET actions = '' WHERE actions IS NULL");
            DB::statement("UPDATE reparations SET nb_leds_remplacees = 0 WHERE nb_leds_remplacees IS NULL");
            DB::statement("UPDATE reparations SET nb_ic_remplaces = 0 WHERE nb_ic_remplaces IS NULL");
            DB::statement("UPDATE reparations SET nb_masques_remplaces = 0 WHERE nb_masques_remplaces IS NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les colonnes ajoutées
        Schema::table('diagnostics', function (Blueprint $table) {
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
        
        Schema::table('reparations', function (Blueprint $table) {
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
