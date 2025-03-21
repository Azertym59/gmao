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
        // Ajouter des colonnes à la table dalles pour la disposition des modules
        Schema::table('dalles', function (Blueprint $table) {
            $table->integer('nb_colonnes')->default(1)->after('nb_modules');
            $table->integer('nb_lignes')->default(1)->after('nb_colonnes');
            $table->enum('disposition_type', ['standard', 'personnalisee'])->default('standard')->after('nb_lignes');
            $table->json('disposition_schema')->nullable()->after('disposition_type');
            $table->enum('mode_emballage', ['flightcase', 'carton', 'palette', 'autre'])->nullable()->after('disposition_schema');
            $table->string('mode_emballage_detail')->nullable()->after('mode_emballage');
        });
        
        // Ajouter une colonne pour la position lettrée du module
        Schema::table('modules', function (Blueprint $table) {
            $table->string('position_lettre')->nullable()->after('reference_module');
            $table->integer('position_x')->nullable()->after('position_lettre');
            $table->integer('position_y')->nullable()->after('position_x');
        });
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