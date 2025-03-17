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
        // Mettre à jour tous les modules avec une résolution 64x64 vers 100x100
        DB::statement('UPDATE modules SET nb_pixels_largeur = 100, nb_pixels_hauteur = 100 WHERE nb_pixels_largeur = 64 AND nb_pixels_hauteur = 64');
        
        // Pour les nouvelles insertions, définir une valeur par défaut
        Schema::table('modules', function (Blueprint $table) {
            $table->integer('nb_pixels_largeur')->default(100)->change();
            $table->integer('nb_pixels_hauteur')->default(100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les résolutions à 64x64
        DB::statement('UPDATE modules SET nb_pixels_largeur = 64, nb_pixels_hauteur = 64 WHERE nb_pixels_largeur = 100 AND nb_pixels_hauteur = 100');
        
        // Remettre les valeurs par défaut à null
        Schema::table('modules', function (Blueprint $table) {
            $table->integer('nb_pixels_largeur')->default(null)->change();
            $table->integer('nb_pixels_hauteur')->default(null)->change();
        });
    }
};
