<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MarqueAutocomplete extends Component
{
    /**
     * Le texte du libellé du champ
     *
     * @var string
     */
    public $label;

    /**
     * Le texte du placeholder
     *
     * @var string
     */
    public $placeholder;

    /**
     * Si le champ est requis
     *
     * @var boolean
     */
    public $required;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $label = 'Marque',
        string $placeholder = 'Recherchez ou saisissez une marque...',
        bool $required = false
    ) {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.marque-autocomplete');
    }
}