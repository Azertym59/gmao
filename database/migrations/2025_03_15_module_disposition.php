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
        // Vérifions si les colonnes existent déjà
        if (!Schema::hasColumn('dalles', 'nb_colonnes')) {
            Schema::table('dalles', function (Blueprint $table) {
                $table->integer('nb_colonnes')->default(1)->after('nb_modules');
            });
        }
        
        if (!Schema::hasColumn('dalles', 'nb_lignes')) {
            Schema::table('dalles', function (Blueprint $table) {
                $table->integer('nb_lignes')->default(1)->after('nb_colonnes');
            });
        }
        
        if (!Schema::hasColumn('dalles', 'disposition_type')) {
            Schema::table('dalles', function (Blueprint $table) {
                $table->enum('disposition_type', ['standard', 'personnalisee'])->default('standard')->after('nb_lignes');
            });
        }
        
        if (!Schema::hasColumn('dalles', 'disposition_schema')) {
            Schema::table('dalles', function (Blueprint $table) {
                $table->json('disposition_schema')->nullable()->after('disposition_type');
            });
        }
        
        if (!Schema::hasColumn('dalles', 'mode_emballage')) {
            Schema::table('dalles', function (Blueprint $table) {
                $table->enum('mode_emballage', ['flightcase', 'carton', 'palette', 'autre'])->nullable()->after('disposition_schema');
            });
        }
        
        if (!Schema::hasColumn('dalles', 'mode_emballage_detail')) {
            Schema::table('dalles', function (Blueprint $table) {
                $table->string('mode_emballage_detail')->nullable()->after('mode_emballage');
            });
        }
        
        // Ajouter une colonne pour la position lettrée du module
        if (!Schema::hasColumn('modules', 'position_lettre')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->string('position_lettre')->nullable()->after('reference_module');
            });
        }
        
        if (!Schema::hasColumn('modules', 'position_x')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->integer('position_x')->nullable()->after('position_lettre');
            });
        }
        
        if (!Schema::hasColumn('modules', 'position_y')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->integer('position_y')->nullable()->after('position_x');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dalles', function (Blueprint $table) {
            $table->dropColumn([
                'nb_colonnes', 
                'nb_lignes', 
                'disposition_type', 
                'disposition_schema',
                'mode_emballage',
                'mode_emballage_detail'
            ]);
        });
        
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn(['position_lettre', 'position_x', 'position_y']);
        });
    }
};