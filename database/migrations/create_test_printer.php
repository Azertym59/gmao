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
        // Vérifier si la table existe et s'il n'y a pas d'imprimante Brother
        if (Schema::hasTable('printers')) {
            // Supprimer toutes les imprimantes existantes pour simplifier
            DB::table('printers')->delete();
            
            // Créer une imprimante Brother QL-820NWB pour le test
            DB::table('printers')->insert([
                'name' => 'Brother QL-820NWB (Test)',
                'type' => 'printnode',
                'is_default' => true,
                'options' => json_encode([
                    'model' => 'QL-820NWB',
                    'dpi' => 300,
                    'width' => 62,
                    'height' => 70,
                    'connection_type' => 'printnode',
                    'label_format' => 'dk22205',
                    'printer_id' => '12345' // ID fictif pour les tests
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('printers')) {
            DB::table('printers')
                ->where('name', 'Brother QL-820NWB (Test)')
                ->delete();
        }
    }
};