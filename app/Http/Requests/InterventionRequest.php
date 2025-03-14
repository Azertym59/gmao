<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterventionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'module_id' => 'required|exists:modules,id',
            'technicien_id' => 'nullable|exists:users,id',
        ];
        
        // Si nous sommes en train de mettre à jour une intervention
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = array_merge($rules, [
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'temps_total' => 'nullable|integer|min:0',
                'etat' => [
                    'required',
                    Rule::in(['En cours', 'Diagnostic terminé', 'Terminée']),
                ],
                
                // Diagnostic
                'diagnostic' => 'nullable|array',
                'diagnostic.description' => 'required_with:diagnostic|string',
                'diagnostic.conclusion' => 'required_with:diagnostic|string',
                'diagnostic.composant_defectueux' => 'nullable|string',
                
                // Réparation
                'reparation' => 'nullable|array',
                'reparation.description' => 'required_with:reparation|string',
                'reparation.actions' => 'required_with:reparation|string',
                'reparation.pieces_remplacees' => 'nullable|string',
                'reparation.resultat' => [
                    'required_with:reparation',
                    Rule::in(['Réparé', 'Non réparable', 'En attente de pièces']),
                ],
            ]);
        }
        
        return $rules;
    }
    
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'module_id' => 'module',
            'technicien_id' => 'technicien',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'temps_total' => 'temps total',
            'etat' => 'état',
            'diagnostic.description' => 'description du diagnostic',
            'diagnostic.conclusion' => 'conclusion du diagnostic',
            'diagnostic.composant_defectueux' => 'composant défectueux',
            'reparation.description' => 'description de la réparation',
            'reparation.actions' => 'actions de réparation',
            'reparation.pieces_remplacees' => 'pièces remplacées',
            'reparation.resultat' => 'résultat de la réparation',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'diagnostic.required_with' => 'Les informations de diagnostic sont requises.',
            'reparation.required_with' => 'Les informations de réparation sont requises.',
        ];
    }
}