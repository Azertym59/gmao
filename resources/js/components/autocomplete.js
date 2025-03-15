export default function clientAutocomplete() {
  return {
    query: '',
    results: [],
    isLoading: false,
    selectedClient: null,
    showResults: false,
    
    init() {
      this.$watch('query', (value) => {
        if (value.length > 2) {
          this.searchClients();
        } else {
          this.results = [];
          this.showResults = false;
        }
      });
    },
    
    searchClients() {
      this.isLoading = true;
      console.log('Recherche clients avec:', this.query);
      
      // Utiliser le script de debug directement plutôt que la route API
      fetch(`/debug-api.php?term=${encodeURIComponent(this.query)}`)
        .then(response => {
          console.log('Statut de la réponse:', response.status);
          if (!response.ok) {
            throw new Error(`Erreur HTTP! statut: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.log('Données reçues:', data);
          this.results = data;
          this.showResults = this.results.length > 0;
        })
        .catch(error => {
          console.error('Erreur lors de la recherche de clients:', error);
          // Création d'un fallback pour le débogage
          this.results = [];
          this.showResults = false;
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    
    selectClient(client) {
      this.selectedClient = client;
      this.query = client.display_text;
      this.showResults = false;
      
      // Si un champ client_id existe, le remplir avec l'ID du client
      const clientIdField = document.getElementById('client_id');
      if (clientIdField) {
        clientIdField.value = client.id;
        
        // Déclencher les événements de changement pour informer les autres composants
        clientIdField.dispatchEvent(new Event('change'));
      }
      
      // Remplir les champs du formulaire si nécessaire
      this.fillFormFields(client);
    },
    
    fillFormFields(client) {
      // Correspondances entre les propriétés du client et les champs de formulaire
      const fieldMappings = {
        'nom': 'nom',
        'prenom': 'prenom', 
        'societe': 'societe',
        'email': 'email',
        'telephone': 'telephone',
        'adresse': 'adresse',
        'code_postal': 'code_postal',
        'ville': 'ville',
        'pays': 'pays'
      };
      
      // Remplir les champs s'ils existent
      Object.entries(fieldMappings).forEach(([clientProp, fieldId]) => {
        const field = document.getElementById(fieldId);
        if (field && client[clientProp] !== undefined) {
          field.value = client[clientProp] || '';
        }
      });
    },
    
    resetSelection() {
      this.selectedClient = null;
      this.query = '';
      
      // Réinitialiser le champ client_id s'il existe
      const clientIdField = document.getElementById('client_id');
      if (clientIdField) {
        clientIdField.value = '';
        clientIdField.dispatchEvent(new Event('change'));
      }
    }
  };
}