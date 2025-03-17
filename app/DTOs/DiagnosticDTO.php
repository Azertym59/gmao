<?php

namespace App\DTOs;

/**
 * Data Transfer Object pour les données de diagnostic
 */
class DiagnosticDTO
{
    /**
     * @var int L'ID de l'intervention associée
     */
    public int $intervention_id;
    
    /**
     * @var int Le nombre de LEDs en panne
     */
    public int $nb_leds_hs;
    
    /**
     * @var int Le nombre d'ICs en panne
     */
    public int $nb_ic_hs;
    
    /**
     * @var int Le nombre de masques en panne
     */
    public int $nb_masques_hs;
    
    /**
     * @var string|null Les remarques du diagnostic
     */
    public ?string $remarques;
    
    /**
     * @var bool Si un fake PCB est nécessaire
     */
    public bool $pose_fake_pcb;
    
    /**
     * @var string|null La cause du problème
     */
    public ?string $cause;
    
    /**
     * @var string|null Le numéro de série mis à jour du module
     */
    public ?string $numero_serie;
    
    /**
     * Crée un DTO à partir des données de requête
     */
    public static function fromRequest($request): self
    {
        $dto = new self();
        $dto->intervention_id = $request->input('intervention_id');
        $dto->nb_leds_hs = max(0, (int) $request->input('diagnostic_nb_leds_hs', 0));
        $dto->nb_ic_hs = max(0, (int) $request->input('diagnostic_nb_ic_hs', 0));
        $dto->nb_masques_hs = max(0, (int) $request->input('diagnostic_nb_masques_hs', 0));
        $dto->remarques = $request->input('diagnostic_remarques');
        $dto->pose_fake_pcb = $request->has('diagnostic_pose_fake_pcb');
        $dto->cause = $request->input('diagnostic_cause');
        $dto->numero_serie = $request->input('numero_serie');
        
        return $dto;
    }
    
    /**
     * Convertit le DTO en tableau pour l'insertion/mise à jour en base de données
     */
    public function toArray(): array
    {
        return [
            'intervention_id' => $this->intervention_id,
            'nb_leds_hs' => $this->nb_leds_hs,
            'nb_ic_hs' => $this->nb_ic_hs,
            'nb_masques_hs' => $this->nb_masques_hs,
            'remarques' => $this->remarques,
            'pose_fake_pcb' => $this->pose_fake_pcb,
            'cause' => $this->cause,
        ];
    }
}
