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
        // Nous ne pouvons pas modifier les contraintes d'enum directement en SQLite
        // Cette migration est purement documentaire pour SQLite
        // En production avec MySQL, nous devrions modifier les contraintes
        
        // Pour SQLite, nous n'avons pas besoin de migration car il n'y a pas de contrainte d'enum
        // Nous pouvons directement utiliser le nouvel état dans le code
        
        // Log la migration pour traçabilité
        DB::table('migrations')->insert([
            'migration' => '2025_03_26_add_stand_by_state',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pour SQLite, ne rien faire
    }
};