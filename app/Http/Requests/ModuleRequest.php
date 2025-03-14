<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuleRequest extends FormRequest
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
            'reference' => [
                'required',
                'string',
                'max:50',
            ],
            'type' => 'required|string|max:50',
            'largeur' => 'required|numeric|min:1',
            'hauteur' => 'required|numeric|min:1',
            'dalle_id' => 'required|exists:dalles,id',
            'position_x' => 'nullable|integer|min:0',
            'position_y' => 'nullable|integer|min:0',
            'etat' => [
                'nullable',
                Rule::in(['En stock', 'En préparation', 'En diagnostic', 'En réparation', 'Terminé', 'Défectueux']),
            ],
            'est_occupe' => 'nullable|boolean'
        ];
        
        // Si nous créons un nouveau module, nous devons vérifier que la référence est unique
        if ($this->isMethod('POST')) {
            $rules['reference'][] = 'unique:modules,reference';
        }
        
        // Si nous mettons à jour un module existant, nous devons vérifier que la référence est unique sauf pour ce module
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['reference'][] = Rule::unique('modules', 'reference')->ignore($this->route('module'));
        }
        
        return $rules;
    }
    
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'reference' => 'référence',
            'type' => 'type',
            'largeur' => 'largeur',
            'hauteur' => 'hauteur',
            'dalle_id' => 'dalle',
            'position_x' => 'position X',
            'position_y' => 'position Y',
            'etat' => 'état',
            'est_occupe' => 'occupé'
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir la valeur est_occupe en boolean
        if ($this->has('est_occupe')) {
            $this->merge([
                'est_occupe' => filter_var($this->est_occupe, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }
}