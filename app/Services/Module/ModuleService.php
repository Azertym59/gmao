<?php

namespace App\Services\Module;

use App\Models\Dalle;
use App\Models\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuleService
{
    /**
     * Crée plusieurs modules pour une dalle donnée
     */
    public function createModules(Dalle $dalle, array $modulesData): array
    {
        DB::beginTransaction();
        
        try {
            $modules = [];
            
            foreach ($modulesData as $moduleData) {
                $module = Module::create([
                    'reference' => $moduleData['reference'],
                    'type' => $moduleData['type'],
                    'largeur' => $moduleData['largeur'],
                    'hauteur' => $moduleData['hauteur'],
                    'dalle_id' => $dalle->id,
                    'position_x' => $moduleData['position_x'] ?? null,
                    'position_y' => $moduleData['position_y'] ?? null,
                    'etat' => 'En stock',
                ]);
                
                $modules[] = $module;
            }
            
            DB::commit();
            return $modules;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création des modules: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Génère automatiquement des modules pour une dalle
     * en fonction des dimensions et de la taille standard des modules
     */
    public function generateModulesGrid(Dalle $dalle, int $moduleWidth, int $moduleHeight, string $moduleType): array
    {
        // Calculer le nombre de modules en largeur et hauteur
        $nbModulesX = ceil($dalle->largeur / $moduleWidth);
        $nbModulesY = ceil($dalle->hauteur / $moduleHeight);
        
        $modulesData = [];
        $moduleCount = 1;
        
        for ($y = 0; $y < $nbModulesY; $y++) {
            for ($x = 0; $x < $nbModulesX; $x++) {
                $modulesData[] = [
                    'reference' => $dalle->reference . '-M' . str_pad($moduleCount, 3, '0', STR_PAD_LEFT),
                    'type' => $moduleType,
                    'largeur' => $moduleWidth,
                    'hauteur' => $moduleHeight,
                    'position_x' => $x,
                    'position_y' => $y,
                ];
                
                $moduleCount++;
            }
        }
        
        return $this->createModules($dalle, $modulesData);
    }
    
    /**
     * Met à jour la disposition d'un module
     */
    public function updateModulePosition(Module $module, int $positionX, int $positionY): Module
    {
        $module->position_x = $positionX;
        $module->position_y = $positionY;
        $module->save();
        
        return $module;
    }
    
    /**
     * Récupère la matrice de disposition des modules pour une dalle
     */
    public function getModuleGridLayout(Dalle $dalle): array
    {
        $modules = $dalle->modules()->orderBy('position_y')->orderBy('position_x')->get();
        
        $grid = [];
        
        // Déterminer les dimensions max de la grille
        $maxX = $modules->max('position_x') ?? 0;
        $maxY = $modules->max('position_y') ?? 0;
        
        // Initialiser la grille avec des valeurs nulles
        for ($y = 0; $y <= $maxY; $y++) {
            $grid[$y] = [];
            for ($x = 0; $x <= $maxX; $x++) {
                $grid[$y][$x] = null;
            }
        }
        
        // Remplir la grille avec les modules
        foreach ($modules as $module) {
            if ($module->position_x !== null && $module->position_y !== null) {
                $grid[$module->position_y][$module->position_x] = $module;
            }
        }
        
        return $grid;
    }
    
    /**
     * Retourne des statistiques sur les modules
     */
    public function getModulesStats(): array
    {
        $totalModules = Module::count();
        $enStock = Module::where('etat', 'En stock')->count();
        $enPreparation = Module::where('etat', 'En préparation')->count();
        $enDiagnostic = Module::where('etat', 'En diagnostic')->count();
        $enReparation = Module::where('etat', 'En réparation')->count();
        $termines = Module::where('etat', 'Terminé')->count();
        $defectueux = Module::where('etat', 'Défectueux')->count();
        
        return [
            'total' => $totalModules,
            'en_stock' => $enStock,
            'en_preparation' => $enPreparation,
            'en_diagnostic' => $enDiagnostic,
            'en_reparation' => $enReparation,
            'termines' => $termines,
            'defectueux' => $defectueux,
        ];
    }
}