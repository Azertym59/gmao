<?php

namespace App\DTOs;

/**
 * Data Transfer Object pour les données de réparation
 */
class ReparationDTO
{
    /**
     * @var int L'ID de l'intervention associée
     */
    public int $intervention_id;
    
    /**
     * @var int Le nombre de LEDs remplacées
     */
    public int $nb_leds_remplacees;
    
    /**
     * @var int Le nombre d'ICs remplacés
     */
    public int $nb_ic_remplaces;
    
    /**
     * @var int Le nombre de masques remplacés
     */
    public int $nb_masques_remplaces;
    
    /**
     * @var int Le nombre de fake PCB utilisés
     */
    public int $fake_pcb_nb;
    
    /**
     * @var string|null Les remarques de la réparation
     */
    public ?string $remarques;
    
    /**
     * @var bool Si un fake PCB a été posé
     */
    public bool $fake_pcb_pose;
    
    /**
     * Crée un DTO à partir des données de requête
     */
    public static function fromRequest($request): self
    {
        $dto = new self();
        $dto->intervention_id = $request->input('intervention_id');
        $dto->nb_leds_remplacees = max(0, (int) $request->input('reparation_nb_leds_remplacees', 0));
        $dto->nb_ic_remplaces = max(0, (int) $request->input('reparation_nb_ic_remplaces', 0));
        $dto->nb_masques_remplaces = max(0, (int) $request->input('reparation_nb_masques_remplaces', 0));
        $dto->fake_pcb_nb = max(0, (int) $request->input('reparation_fake_pcb_nb', 0));
        $dto->remarques = $request->input('reparation_remarques');
        $dto->fake_pcb_pose = $request->has('reparation_fake_pcb_pose');
        
        return $dto;
    }
    
    /**
     * Convertit le DTO en tableau pour l'insertion/mise à jour en base de données
     */
    public function toArray(): array
    {
        return [
            'intervention_id' => $this->intervention_id,
            'nb_leds_remplacees' => $this->nb_leds_remplacees,
            'nb_ic_remplaces' => $this->nb_ic_remplaces,
            'nb_masques_remplaces' => $this->nb_masques_remplaces,
            'fake_pcb_nb' => $this->fake_pcb_nb,
            'remarques' => $this->remarques,
            'fake_pcb_pose' => $this->fake_pcb_pose,
        ];
    }
}
