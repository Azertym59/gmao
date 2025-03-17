@props([
    'status',
    'type' => '',
    'customLabel' => ''
])

@php
    // Si le type n'est pas fourni, le déduire du statut
    $statusType = $type ?: match($status) {
        'termine', 'completed', 'success', 'success', 'good', 'ok', 'valid' => 'success',
        'en_cours', 'started', 'in_progress', 'pending', 'warning' => 'warning',
        'defaillant', 'error', 'danger', 'critical', 'failed', 'broken' => 'danger',
        default => 'info'
    };
    
    // Définir la classe CSS en fonction du type
    $cssClass = match($statusType) {
        'success' => 'badge badge-success',
        'warning' => 'badge badge-warning',
        'danger' => 'badge badge-danger',
        default => 'badge badge-info'
    };
    
    // Générer un label lisible si aucun n'est fourni
    $label = $customLabel ?: match($status) {
        'non_commence' => 'Non commencé',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'defaillant' => 'Défaillant',
        default => ucfirst(str_replace('_', ' ', $status))
    };
@endphp

<span {{ $attributes->merge(['class' => $cssClass]) }}>
    {{ $label }}
</span>