/**
 * Composant de recherche avancée de produits
 * Version: 1.0.0 - Date: 2025-03-16
 */

function advancedProductSearch() {
    return {
        // États
        allProducts: [],
        filteredProducts: [],
        selectedProduct: null,
        isSearching: false,
        isLoading: true,
        
        // Filtres
        filters: {
            marque: '',
            modele: '',
            pitch: '',
            bain: '',
            utilisation: ''
        },
        
        // Initialisation
        init() {
            this.isLoading = true;
            
            // Charger tous les produits du catalogue
            fetch('/api/produits/catalogue')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur réseau: ${response.status} ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Produits chargés:', data); // Pour débogage
                    this.allProducts = data;
                    this.filteredProducts = [...data];
                    this.isLoading = false;
                    
                    // Si un ID de catalogue était déjà sélectionné, le récupérer
                    const catalogueId = document.getElementById('catalogue_id').value;
                    if (catalogueId) {
                        const product = this.allProducts.find(p => p.id == catalogueId);
                        if (product) {
                            this.selectedProduct = product;
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des produits:', error);
                    this.isLoading = false;
                    
                    // Afficher un message d'erreur dans la console pour faciliter le débogage
                    console.log('Détails de l\'erreur:', {
                        message: error.message,
                        stack: error.stack
                    });
                    
                    // En cas d'erreur, on utilise des données pré-existantes dans la page
                    this.loadProductsFromDOM();
                    
                    // Afficher des données de test en dernier recours
                    if (this.allProducts.length === 0) {
                        this.allProducts = [
                            {
                                id: 1,
                                marque: 'LIGHTKING',
                                modele: 'RD',
                                pitch: 2.5,
                                utilisation: 'indoor',
                                electronique: 'nova',
                                bain_couleur: ''
                            },
                            {
                                id: 2,
                                marque: 'LIGHTKING',
                                modele: 'RY',
                                pitch: 3.9,
                                utilisation: 'outdoor',
                                electronique: 'nova',
                                bain_couleur: ''
                            },
                            {
                                id: 3,
                                marque: 'TEST',
                                modele: 'PANEL',
                                pitch: 2.6,
                                utilisation: 'indoor',
                                electronique: 'nova',
                                bain_couleur: ''
                            }
                        ];
                        this.filteredProducts = [...this.allProducts];
                        console.log('Chargement des données de test:', this.allProducts);
                    }
                });
                
            // Observer les changements des filtres
            this.$watch('filters', () => {
                this.filterProducts();
            }, { deep: true });
        },
        
        // Charger les produits depuis les options du select existant
        loadProductsFromDOM() {
            const select = document.getElementById('catalogue_id');
            if (!select) return;
            
            const products = [];
            select.querySelectorAll('option').forEach(option => {
                if (option.value) {
                    // Extraire les informations du texte de l'option
                    // Format attendu: "MARQUE MODELE - PITCHmm"
                    const text = option.textContent.trim();
                    const pitchIndex = text.lastIndexOf(' - ');
                    
                    if (pitchIndex > 0) {
                        const mainPart = text.substring(0, pitchIndex);
                        const pitchPart = text.substring(pitchIndex + 3);
                        
                        // Trouver la séparation entre marque et modèle (premier espace)
                        const firstSpace = mainPart.indexOf(' ');
                        let marque = mainPart;
                        let modele = '';
                        
                        if (firstSpace > 0) {
                            marque = mainPart.substring(0, firstSpace);
                            modele = mainPart.substring(firstSpace + 1);
                        }
                        
                        // Extraire la valeur du pitch (enlever "mm")
                        const pitch = parseFloat(pitchPart.replace('mm', ''));
                        
                        products.push({
                            id: option.value,
                            marque: marque,
                            modele: modele,
                            pitch: pitch,
                            utilisation: 'indoor', // valeur par défaut
                            electronique: 'non spécifié',
                            bain_couleur: ''
                        });
                    }
                }
            });
            
            this.allProducts = products;
            this.filteredProducts = [...products];
        },
        
        // Filtrer les produits selon les critères
        filterProducts() {
            this.isSearching = true;
            
            // Appliquer tous les filtres actifs
            this.filteredProducts = this.allProducts.filter(product => {
                // Filter par marque (insensible à la casse)
                if (this.filters.marque && !product.marque.toLowerCase().includes(this.filters.marque.toLowerCase())) {
                    return false;
                }
                
                // Filter par modèle (insensible à la casse)
                if (this.filters.modele && !product.modele.toLowerCase().includes(this.filters.modele.toLowerCase())) {
                    return false;
                }
                
                // Filter par pitch (correspondance exacte)
                if (this.filters.pitch && product.pitch != this.filters.pitch) {
                    return false;
                }
                
                // Filter par bain de couleur (insensible à la casse)
                if (this.filters.bain && 
                    (!product.bain_couleur || !product.bain_couleur.toLowerCase().includes(this.filters.bain.toLowerCase()))) {
                    return false;
                }
                
                // Filter par utilisation (correspondance exacte)
                if (this.filters.utilisation && product.utilisation !== this.filters.utilisation) {
                    return false;
                }
                
                return true;
            });
            
            // Petit délai pour montrer l'animation de recherche
            setTimeout(() => {
                this.isSearching = false;
            }, 300);
        },
        
        // Sélectionner un produit
        selectProduct(product) {
            this.selectedProduct = product;
            // Mettre à jour la valeur cachée pour le formulaire
            document.getElementById('catalogue_id').value = product.id;
        },
        
        // Effacer la sélection
        clearSelection() {
            this.selectedProduct = null;
            document.getElementById('catalogue_id').value = '';
        },
        
        // Réinitialiser les filtres
        resetFilters() {
            this.filters = {
                marque: '',
                modele: '',
                pitch: '',
                bain: '',
                utilisation: ''
            };
            this.filterProducts();
        },
        
        // Suggérer la création d'un nouveau produit avec les critères actuels
        suggestNewProduct() {
            // Basculer vers le formulaire de création
            document.getElementById('from_catalogue_0').click();
            
            // Pré-remplir les champs du formulaire avec les critères de recherche
            if (this.filters.marque) {
                const marqueSearch = document.getElementById('marque-search');
                const marqueField = document.getElementById('marque');
                
                if (marqueSearch) {
                    marqueSearch.value = this.filters.marque;
                    // Simuler un événement input pour déclencher l'autocomplétion
                    marqueSearch.dispatchEvent(new Event('input', { bubbles: true }));
                }
                
                if (marqueField) {
                    marqueField.value = this.filters.marque;
                    // Informer les autres composants du changement
                    const event = new CustomEvent('marque-selected', { 
                        detail: { marque: this.filters.marque } 
                    });
                    window.dispatchEvent(event);
                }
            }
            
            if (this.filters.modele) {
                const modeleSearch = document.getElementById('modele-search');
                const modeleField = document.getElementById('modele');
                
                if (modeleSearch) {
                    modeleSearch.value = this.filters.modele;
                    // Simuler un événement input pour déclencher l'autocomplétion
                    modeleSearch.dispatchEvent(new Event('input', { bubbles: true }));
                }
                
                if (modeleField) {
                    modeleField.value = this.filters.modele;
                    // Déclencher un événement change
                    modeleField.dispatchEvent(new Event('change'));
                }
            }
            
            if (this.filters.pitch) {
                document.getElementById('pitch').value = this.filters.pitch;
            }
            
            if (this.filters.bain) {
                document.getElementById('bain_couleur').value = this.filters.bain;
            }
            
            if (this.filters.utilisation) {
                document.getElementById('utilisation').value = this.filters.utilisation;
            }
        }
    };
}