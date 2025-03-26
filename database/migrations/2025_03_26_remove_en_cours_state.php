<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cette migration met à jour les modules qui sont en état 'en_cours' vers 'stand_by'
     */
    public function up(): void
    {
        // Mettre à jour tous les modules qui sont en état "en_cours" vers "stand_by"
        DB::table('modules')
            ->where('etat', 'en_cours')
            ->update(['etat' => 'stand_by']);
        
        // Log la migration pour traçabilité
        DB::table('migrations')->insert([
            'migration' => '2025_03_26_remove_en_cours_state',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à faire car nous ne pouvons pas déterminer quels modules étaient exactement en état "en_cours" avant
    }
};