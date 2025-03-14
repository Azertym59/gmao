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
        Schema::table('interventions', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign(['technicien_id']);
            
            // Modifier la colonne pour accepter NULL
            $table->foreignId('technicien_id')->nullable()->change();
            
            // Recréer la contrainte de clé étrangère qui accepte NULL
            $table->foreign('technicien_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign(['technicien_id']);
            
            // Modifier la colonne pour qu'elle ne puisse pas être NULL
            $table->foreignId('technicien_id')->nullable(false)->change();
            
            // Recréer la contrainte de clé étrangère qui n'accepte pas NULL
            $table->foreign('technicien_id')->references('id')->on('users');
        });
    }
};
