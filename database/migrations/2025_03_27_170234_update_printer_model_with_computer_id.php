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
        // Pas besoin de modifier la structure de table car les options sont stockées dans une colonne JSON
        
        // Mettre à jour les imprimantes PrintNode existantes avec les données des nouvelles imprimantes
        
        // Imprimante 1: Brother QL-820NWB (TIMOXY-OMEN)
        $printer1 = DB::table('printers')->where('name', 'Brother QL-820NWB (TIMOXY-OMEN)')->first();
        if ($printer1) {
            $options = json_decode($printer1->options, true) ?: [];
            $options['printnode_id'] = 74188357;
            $options['computer_id'] = 640752;
            
            DB::table('printers')
                ->where('id', $printer1->id)
                ->update([
                    'options' => json_encode($options),
                    'updated_at' => now()
                ]);
        }
        
        // Imprimante 2: Brother QL-820NWB (THOMAS)
        $printer2 = DB::table('printers')->where('name', 'Brother QL-820NWB (THOMAS)')->first();
        if ($printer2) {
            $options = json_decode($printer2->options, true) ?: [];
            $options['printnode_id'] = 74222723;
            $options['computer_id'] = 643685;
            
            DB::table('printers')
                ->where('id', $printer2->id)
                ->update([
                    'options' => json_encode($options),
                    'updated_at' => now()
                ]);
        }
        
        // Imprimante 3: Brother QL-820NWB (ADMINISTRATOR)
        $printer3 = DB::table('printers')->where('name', 'Brother QL-820NWB (ADMINISTRATOR)')->first();
        if ($printer3) {
            $options = json_decode($printer3->options, true) ?: [];
            $options['printnode_id'] = 74222709;
            $options['computer_id'] = 643676;
            
            DB::table('printers')
                ->where('id', $printer3->id)
                ->update([
                    'options' => json_encode($options),
                    'updated_at' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à annuler spécifiquement
    }
};
