# Guide des composants Blade

## Composants de boutons

Utilisez ces composants pour standardiser les boutons dans toute l'application.

### Boutons d'action de base

- `x-primary-button` : Bouton bleu principal pour les actions principales *(submit, ajouter, etc)*
- `x-secondary-button` : Bouton gris pour les actions secondaires *(cancel, reset, etc)*
- `x-success-button` : Bouton vert pour les actions de succès *(valider, confirmer, etc)*
- `x-danger-button` : Bouton rouge pour les actions dangereuses *(supprimer, annuler, etc)*
- `x-info-button` : Bouton bleu clair pour les actions informatives
- `x-outline-button` : Bouton avec contour et fond transparent
- `x-link-button` : Bouton style lien texte
- `x-action-button` : Bouton d'action discret (fond gris foncé)

Exemple d'utilisation :
```html
<x-primary-button>Enregistrer</x-primary-button>

<!-- Pour faire un lien (balise <a>) avec le style d'un bouton -->
<x-primary-button tag="a" href="{{ route('home') }}">Retour</x-primary-button>

<!-- Avec des classes supplémentaires -->
<x-danger-button class="mt-4">Supprimer</x-danger-button>
```

### Boutons de navigation et d'action

- `x-back-button` : Bouton de retour avec flèche (utilise url()->previous() par défaut)
- `x-add-button` : Bouton d'ajout avec icône plus
- `x-edit-button` : Bouton de modification avec icône crayon
- `x-view-button` : Bouton pour voir/consulter avec icône œil
- `x-delete-button` : Bouton de suppression avec icône poubelle (avec confirmation)
- `x-email-button` : Bouton d'envoi d'email avec icône enveloppe
- `x-logout-button` : Bouton de déconnexion avec dominante rouge (génère un formulaire de logout)

Exemple d'utilisation :
```html
<!-- Bouton de retour (route facultative, utilise url()->previous() par défaut) -->
<x-back-button />
<x-back-button :route="route('home')" />

<!-- Bouton d'ajout (route obligatoire) -->
<x-add-button :route="route('items.create')" />

<!-- Bouton de modification (route obligatoire) -->
<x-edit-button :route="route('items.edit', $item)" />

<!-- Bouton pour voir les détails (route obligatoire) -->
<x-view-button :route="route('items.show', $item)" />

<!-- Bouton de suppression avec confirmation (route obligatoire) -->
<x-delete-button :route="route('items.destroy', $item)" />
<x-delete-button :route="route('items.destroy', $item)" :confirmText="'Êtes-vous sûr de vouloir supprimer cet élément ?'" />

<!-- Bouton d'envoi d'email (spécifique au contexte des chantiers) -->
<x-email-button :chantier="$chantier" type="created">
    Email création
</x-email-button>

<!-- Bouton de déconnexion -->
<x-logout-button>
    Déconnexion
</x-logout-button>

<!-- Bouton de déconnexion avec route spécifique -->
<x-logout-button route="client.logout">
    Déconnexion
</x-logout-button>

<!-- Bouton de déconnexion avec styles spécifiques -->
<x-logout-button class="btn-logout-compact">
    Déconnexion
</x-logout-button>
```

### Boutons de navigation spécialisés

- `x-nav-button` : Bouton pour la navigation principale (avec support d'état actif)
- `x-tab-button` : Bouton pour les onglets (avec support d'état actif)

Exemple d'utilisation :
```html
<!-- Bouton de navigation avec état actif -->
<x-nav-button :route="route('dashboard')" :active="request()->routeIs('dashboard')">
    Dashboard
</x-nav-button>

<!-- Avec une icône personnalisée -->
<x-nav-button :route="route('dashboard')" :active="request()->routeIs('dashboard')" :icon="'<svg class=\"h-5 w-5\" ... ></svg>'">
    Dashboard
</x-nav-button>

<!-- Bouton d'onglet -->
<x-tab-button :active="$activeTab === 'general'">Général</x-tab-button>
```

### Boutons d'icônes

Le composant `x-icon-button` permet de créer des boutons ronds contenant uniquement une icône, avec un effet tooltip au survol.

Exemple d'utilisation :
```html
<!-- Bouton icône basique -->
<x-icon-button 
    type="primary" 
    tooltip="Ajouter un élément" 
    tooltipPosition="bottom"
    size="md">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
    </svg>
</x-icon-button>

<!-- Bouton icône lien -->
<x-icon-button 
    tag="a" 
    href="{{ route('items.edit', $item) }}" 
    type="secondary" 
    tooltip="Modifier" 
    tooltipPosition="top"
    size="sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
    </svg>
</x-icon-button>
```

Paramètres:
- `tag`: 'button' (défaut) ou 'a'
- `href`: URL si tag='a' (défaut: '#')
- `type`: 'primary' (défaut), 'secondary', 'success', 'danger', 'info', 'outline', 'action'
- `tooltip`: Texte à afficher au survol (défaut: vide)
- `tooltipPosition`: 'top', 'bottom' (défaut), 'left', 'right'
- `size`: 'xs', 'sm', 'md' (défaut), 'lg', 'xl'

### Classes CSS spéciales

Des classes CSS spéciales peuvent être ajoutées aux boutons pour des effets visuels avancés:

```html
<!-- Bouton avec effet de pulsation -->
<x-primary-button class="pulse-primary">
    Attention requise
</x-primary-button>

<!-- Bouton avec effet d'illumination -->
<x-primary-button class="btn-glow">
    Nouveau
</x-primary-button>

<!-- Bouton avec effet arc-en-ciel -->
<x-primary-button class="btn-rainbow">
    Célébration
</x-primary-button>

<!-- Bouton avec effet néon -->
<x-outline-button class="btn-neon">
    Mode néon
</x-outline-button>

<!-- Bouton avec effet 3D -->
<x-primary-button class="btn-3d">
    Bouton 3D
</x-primary-button>
```

Ces classes sont particulièrement utiles pour les boutons qui nécessitent une attention particulière ou pour des fonctionnalités spéciales.