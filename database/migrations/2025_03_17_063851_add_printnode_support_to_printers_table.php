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
        // Nous ne modifions pas la structure de la table car les options sont stockées 
        // dans une colonne JSON, mais nous allons mettre à jour les printers existants
        // pour ajouter un type 'brother_label' si nécessaire
        
        // Vérifier d'abord si la table existe
        if (Schema::hasTable('printers')) {
            // Ajout d'un nouveau type d'imprimante pour Brother si aucune Brother n'existe
            $brotherPrinterExists = DB::table('printers')
                ->where('name', 'LIKE', '%Brother%')
                ->where('type', 'label')
                ->exists();
                
            if (!$brotherPrinterExists) {
                // Créer une imprimante Brother par défaut pour PrintNode
                DB::table('printers')->insert([
                    'name' => 'Brother QL-820NWB',
                    'type' => 'brother_label',
                    'is_default' => true,
                    'options' => json_encode([
                        'model' => 'QL-820NWB',
                        'dpi' => 300,
                        'label_width' => 62,
                        'label_height' => 100,
                        'connection_type' => 'printnode',
                        'label_format' => 'dk22205',
                        'printnode_id' => env('PRINTNODE_PRINTER_ID', null)
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Si une autre imprimante était par défaut, la désactiver
                DB::table('printers')
                    ->where('id', '!=', DB::getPdo()->lastInsertId())
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les imprimantes de type brother_label
        if (Schema::hasTable('printers')) {
            DB::table('printers')
                ->where('type', 'brother_label')
                ->delete();
        }
    }
};
