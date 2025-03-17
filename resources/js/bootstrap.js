import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Exposer les fonctions d'autocomplétion globalement
import { marqueAutocomplete, modeleAutocomplete, driverAutocomplete } from './components/product-autocomplete';
window.marqueAutocomplete = marqueAutocomplete;
window.modeleAutocomplete = modeleAutocomplete;
window.driverAutocomplete = driverAutocomplete;
