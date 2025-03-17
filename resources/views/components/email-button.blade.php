@props([
    'chantier',
    'type',
    'buttonType' => 'info',
    'size' => 'md',
    'tooltip' => '',
    'tooltipPosition' => 'top',
    'effectClass' => ''
])

@php
    $tooltipText = match($type) {
        'created' => $tooltip ?: 'Email création',
        'started' => $tooltip ?: 'Email interventions',
        'completed' => $tooltip ?: 'Email finalisation',
        default => $tooltip ?: 'Envoyer email'
    };
    
    // Définir la classe d'effet en fonction du type d'email si non spécifiée
    $effect = $effectClass ?: match($type) {
        'created' => 'btn-glow',
        'started' => 'pulse-primary',
        'completed' => 'btn-rainbow',
        default => ''
    };
    
    $formId = "email-{$type}-form-" . uniqid();
@endphp

<span class="inline-block">
    <form id="{{ $formId }}" action="{{ route('emails.chantier', $chantier) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="email_type" value="{{ $type }}">
    </form>
    
    <x-icon-button 
        tag="button" 
        type="{{ $buttonType }}" 
        tooltip="{{ $tooltipText }}" 
        tooltipPosition="{{ $tooltipPosition }}"
        size="{{ $size }}" 
        class="{{ $effect }}"
        {{ $attributes }}
        onclick="document.getElementById('{{ $formId }}').submit();">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </x-icon-button>
</span>
