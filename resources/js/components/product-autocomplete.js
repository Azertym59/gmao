/**
 * Composant d'autocomplétion pour les marques, modèles et drivers de produits
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
      
      // Vérifier si la valeur est déjà remplie (important pour l'initialisation)
      if (this.query && this.query.length > 1) {
        this.searchMarques();
      }
    },
    
    // Recherche des marques
    searchMarques() {
      this.isLoading = true;
      console.log('Recherche marques avec:', this.query);
      
      // Utiliser le script debug-marques.php pour être cohérent avec les autres composants d'autocomplete
      fetch(`/debug-marques.php?term=${encodeURIComponent(this.query)}`)
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
          // En cas d'erreur, essayer l'API standard
          fetch(`/api/marques?term=${encodeURIComponent(this.query)}`)
            .then(response => response.json())
            .then(data => {
              this.results = data;
              this.showResults = this.results.length > 0;
            })
            .catch(() => {
              this.results = [];
              this.showResults = false;
            });
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
      
      // Vérifier si la valeur est déjà remplie (important pour l'initialisation)
      // Essayer de récupérer la marque existante
      const marqueField = document.getElementById('marque');
      if (marqueField && marqueField.value) {
        this.marque = marqueField.value;
      }
      
      // Si la requête et la marque sont déjà remplies, lancer la recherche
      if (this.query && this.query.length > 1 && this.marque) {
        this.searchModeles();
      }
    },
    
    // Recherche des modèles
    searchModeles() {
      if (!this.marque) {
        console.warn('Impossible de rechercher des modèles sans marque sélectionnée');
        return;
      }
      
      this.isLoading = true;
      console.log(`Recherche modèles pour ${this.marque} avec:`, this.query);
      
      // Utiliser le script debug-modeles.php pour être cohérent avec les autres composants d'autocomplete
      fetch(`/debug-modeles.php?term=${encodeURIComponent(this.query)}&marque=${encodeURIComponent(this.marque)}`)
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
          // En cas d'erreur, essayer l'API standard
          fetch(`/api/modeles?term=${encodeURIComponent(this.query)}&marque=${encodeURIComponent(this.marque)}`)
            .then(response => response.json())
            .then(data => {
              this.results = data;
              this.showResults = this.results.length > 0;
            })
            .catch(() => {
              this.results = [];
              this.showResults = false;
            });
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

/**
 * Composant d'autocomplétion pour les drivers IC
 */
export function driverAutocomplete() {
  return {
    query: '',
    results: [],
    isLoading: false,
    selectedDriver: null,
    showResults: false,
    
    init() {
      // Surveillance des changements dans le champ de recherche
      this.$watch('query', (value) => {
        if (value.length > 1) {
          this.searchDrivers();
        } else {
          this.results = [];
          this.showResults = false;
        }
      });
      
      // Vérifier si la valeur est déjà remplie (important pour l'initialisation)
      if (this.query && this.query.length > 1) {
        this.searchDrivers();
      }
    },
    
    // Recherche des drivers
    searchDrivers() {
      this.isLoading = true;
      console.log('Recherche drivers avec:', this.query);
      
      // Liste prédéfinie de drivers couramment utilisés
      const commonDrivers = [
        { id: 'ICN2038', text: 'ICN2038' },
        { id: 'ICN2037', text: 'ICN2037' },
        { id: 'MBI5042', text: 'MBI5042' },
        { id: 'MBI5041', text: 'MBI5041' },
        { id: 'MBI5040', text: 'MBI5040' },
        { id: 'MBI5039', text: 'MBI5039' },
        { id: 'MBI5038', text: 'MBI5038' },
        { id: 'MBI5037', text: 'MBI5037' },
        { id: 'MBI5036', text: 'MBI5036' },
        { id: 'MBI5035', text: 'MBI5035' },
        { id: 'MBI5034', text: 'MBI5034' },
        { id: 'MBI5033', text: 'MBI5033' },
        { id: 'MBI5032', text: 'MBI5032' },
        { id: 'MBI5031', text: 'MBI5031' },
        { id: 'MBI5030', text: 'MBI5030' },
        { id: 'MBI5026', text: 'MBI5026' },
        { id: 'MBI5024', text: 'MBI5024' },
        { id: 'RFD5T245', text: 'RFD5T245' },
        { id: 'TB62726', text: 'TB62726' },
        { id: 'TLC5940', text: 'TLC5940' }
      ];
      
      // Filtrer la liste selon la recherche
      const filtered = commonDrivers.filter(driver => 
        driver.text.toLowerCase().includes(this.query.toLowerCase())
      );
      
      // Simuler un délai réseau (uniquement pour l'UX)
      setTimeout(() => {
        this.results = filtered;
        this.showResults = this.results.length > 0;
        this.isLoading = false;
      }, 200);
    },
    
    // Sélection d'un driver
    selectDriver(driver) {
      this.selectedDriver = driver;
      this.query = driver.text;
      this.showResults = false;
      
      // Remplir le champ de driver (si disponible)
      const driverField = document.getElementById('driver');
      if (driverField) {
        driverField.value = driver.text;
        driverField.dispatchEvent(new Event('change'));
      }
    },
    
    // Cette méthode s'assure que la valeur est enregistrée quand on quitte le champ
    onBlur() {
      // Si l'utilisateur a saisi directement une valeur sans sélectionner dans l'autocomplétion
      if (this.query && this.query.trim() !== '') {
        const driverField = document.getElementById('driver');
        if (driverField) {
          driverField.value = this.query;
          driverField.dispatchEvent(new Event('change'));
        }
      }
    },
    
    // Réinitialiser la sélection
    resetSelection() {
      this.selectedDriver = null;
      this.query = '';
      
      // Réinitialiser le champ de driver
      const driverField = document.getElementById('driver');
      if (driverField) {
        driverField.value = '';
        driverField.dispatchEvent(new Event('change'));
      }
    }
  };
}