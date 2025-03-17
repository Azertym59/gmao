/**
 * Composant d'autocomplétion pour les marques et modèles de produits
 */
export function marqueAutocomplete() {
  return {
    query: '',
    results: [],
    isLoading: false,
    selectedMarque: null,
    showResults: false,
    
    init() {
      // Surveillance des changements dans le champ de recherche
      this.$watch('query', (value) => {
        if (value.length > 1) {
          this.searchMarques();
        } else {
          this.results = [];
          this.showResults = false;
        }
      });
    },
    
    // Recherche des marques
    searchMarques() {
      this.isLoading = true;
      console.log('Recherche marques avec:', this.query);
      
      fetch(`/api/marques?term=${encodeURIComponent(this.query)}`)
        .then(response => {
          console.log('Statut de la réponse marques:', response.status);
          if (!response.ok) {
            throw new Error(`Erreur HTTP! statut: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.log('Marques reçues:', data);
          this.results = data;
          this.showResults = this.results.length > 0;
        })
        .catch(error => {
          console.error('Erreur lors de la recherche de marques:', error);
          this.results = [];
          this.showResults = false;
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    
    // Sélection d'une marque
    selectMarque(marque) {
      this.selectedMarque = marque;
      this.query = marque.text;
      this.showResults = false;
      
      // Informer les autres composants du changement
      const event = new CustomEvent('marque-selected', { 
        detail: { marque: marque.text } 
      });
      window.dispatchEvent(event);
      
      // Remplir le champ de marque (si disponible)
      const marqueField = document.getElementById('marque');
      if (marqueField) {
        marqueField.value = marque.text;
        marqueField.dispatchEvent(new Event('change'));
      }
    },
    
    // Cette méthode s'assure que la valeur est enregistrée quand on quitte le champ
    onBlur() {
      // Si l'utilisateur a saisi directement une valeur sans sélectionner dans l'autocomplétion
      if (this.query && this.query.trim() !== '') {
        const marqueField = document.getElementById('marque');
        if (marqueField) {
          marqueField.value = this.query;
          marqueField.dispatchEvent(new Event('change'));
        }
      }
    },
    
    // Réinitialiser la sélection
    resetSelection() {
      this.selectedMarque = null;
      this.query = '';
      
      // Réinitialiser le champ de marque
      const marqueField = document.getElementById('marque');
      if (marqueField) {
        marqueField.value = '';
        marqueField.dispatchEvent(new Event('change'));
      }
    }
  };
}

/**
 * Composant d'autocomplétion pour les modèles de produits
 */
export function modeleAutocomplete() {
  return {
    query: '',
    marque: '',
    results: [],
    isLoading: false,
    selectedModele: null,
    showResults: false,
    
    init() {
      // Surveillance des changements dans le champ de recherche
      this.$watch('query', (value) => {
        if (value.length > 1 && this.marque) {
          this.searchModeles();
        } else {
          this.results = [];
          this.showResults = false;
        }
      });
      
      // Écouter les événements de sélection de marque
      window.addEventListener('marque-selected', (event) => {
        this.marque = event.detail.marque;
        // Réinitialiser le modèle quand la marque change
        this.resetSelection();
      });
    },
    
    // Recherche des modèles
    searchModeles() {
      if (!this.marque) {
        console.warn('Impossible de rechercher des modèles sans marque sélectionnée');
        return;
      }
      
      this.isLoading = true;
      console.log(`Recherche modèles pour ${this.marque} avec:`, this.query);
      
      fetch(`/api/modeles?term=${encodeURIComponent(this.query)}&marque=${encodeURIComponent(this.marque)}`)
        .then(response => {
          console.log('Statut de la réponse modèles:', response.status);
          if (!response.ok) {
            throw new Error(`Erreur HTTP! statut: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.log('Modèles reçus:', data);
          this.results = data;
          this.showResults = this.results.length > 0;
        })
        .catch(error => {
          console.error('Erreur lors de la recherche de modèles:', error);
          this.results = [];
          this.showResults = false;
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    
    // Sélection d'un modèle
    selectModele(modele) {
      this.selectedModele = modele;
      this.query = modele.text;
      this.showResults = false;
      
      // Remplir le champ de modèle et autres champs associés
      const modeleField = document.getElementById('modele');
      if (modeleField) {
        modeleField.value = modele.text;
        modeleField.dispatchEvent(new Event('change'));
      }
      
      // Remplir les champs pitch et utilisation
      const pitchField = document.getElementById('pitch');
      if (pitchField && modele.pitch) {
        pitchField.value = modele.pitch;
      }
      
      const utilisationField = document.getElementById('utilisation');
      if (utilisationField && modele.utilisation) {
        utilisationField.value = modele.utilisation;
      }
      
      // Informer les autres composants du changement
      const event = new CustomEvent('modele-selected', { 
        detail: { 
          modele: modele.text,
          pitch: modele.pitch,
          utilisation: modele.utilisation
        } 
      });
      window.dispatchEvent(event);
    },
    
    // Cette méthode s'assure que la valeur est enregistrée quand on quitte le champ
    onBlur() {
      // Si l'utilisateur a saisi directement une valeur sans sélectionner dans l'autocomplétion
      if (this.query && this.query.trim() !== '') {
        const modeleField = document.getElementById('modele');
        if (modeleField) {
          modeleField.value = this.query;
          modeleField.dispatchEvent(new Event('change'));
        }
      }
    },
    
    // Réinitialiser la sélection
    resetSelection() {
      this.selectedModele = null;
      this.query = '';
      
      // Réinitialiser le champ de modèle
      const modeleField = document.getElementById('modele');
      if (modeleField) {
        modeleField.value = '';
        modeleField.dispatchEvent(new Event('change'));
      }
    }
  };
}