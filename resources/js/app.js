import './bootstrap';
import Alpine from 'alpinejs';
import clientAutocomplete from './components/autocomplete';
import { marqueAutocomplete, modeleAutocomplete } from './components/product-autocomplete';
import './components/form-formatters'; // Importation des formatters de formulaire

window.Alpine = Alpine;

// Enregistrer les composants Alpine
Alpine.data('clientAutocomplete', clientAutocomplete);
Alpine.data('marqueAutocomplete', marqueAutocomplete);
Alpine.data('modeleAutocomplete', modeleAutocomplete);

Alpine.start();
