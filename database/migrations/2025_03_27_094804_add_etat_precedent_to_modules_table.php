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
        Schema::table('modules', function (Blueprint $table) {
            // Ajouter la colonne etat_precedent qui stocke l'état précédent pour pouvoir le restaurer
            $table->string('etat_precedent')->nullable()->after('etat')->comment('État précédent du module, utilisé lors de la restauration après occupation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            // Supprimer la colonne lors d'un rollback
            $table->dropColumn('etat_precedent');
        });
    }
};
