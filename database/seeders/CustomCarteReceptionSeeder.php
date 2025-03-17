<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomCarteReceptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systems = [
            'nova' => [
                'Novastar Taurus',
                'Novastar MCTRL300',
                'Novastar MCTRL660',
                'Novastar MCTRL4K',
                'Novastar A5s Plus',
                'Novastar A8s Plus',
            ],
            'linsn' => [
                'Linsn TS802',
                'Linsn TS852',
                'Linsn TS902',
                'Linsn RV908',
                'Linsn RV908M',
            ],
            'colorlight' => [
                'Colorlight Z6',
                'Colorlight S6',
                'Colorlight M9',
                'Colorlight X8',
            ],
            'dbstar' => [
                'DBstar HVT11IN',
                'DBstar MRF4IN',
            ],
            'barco' => [
                'Barco E2',
                'Barco S3',
                'Barco EventMaster',
            ],
            'brompton' => [
                'Brompton Tessera S4',
                'Brompton Tessera M2',
                'Brompton Tessera SB40',
                'Brompton Tessera R2',
            ],
        ];
        
        // Mettre à jour tous les produits du catalogue existants en ajoutant les systèmes correspondants
        $produitCatalogue = \App\Models\ProduitCatalogue::get();
        
        foreach ($produitCatalogue as $produit) {
            $electronique = $produit->electronique;
            
            // Si le système électronique figure dans notre liste
            if (array_key_exists($electronique, $systems)) {
                // Mettre à jour avec les cartes disponibles
                $produit->cartes_reception_disponibles = json_encode($systems[$electronique]);
                $produit->save();
                
                $this->command->info("Mise à jour des options de cartes de réception pour {$produit->marque} {$produit->modele} ({$electronique})");
            }
        }
    }
}
