/**
 * Formatage côté client pour une présentation homogène des données
 */
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre en majuscules
    function toUpperCase(input) {
        input.value = input.value.toUpperCase();
    }
    
    // Fonction pour mettre la première lettre en majuscule
    function toCapitalize(input) {
        if (input.value.length > 0) {
            input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
        }
    }
    
    // Fonction pour mettre en minuscules
    function toLowerCase(input) {
        input.value = input.value.toLowerCase();
    }
    
    // Fonction pour nettoyer les espaces dans les codes postaux
    function cleanPostalCode(input) {
        input.value = input.value.replace(/\s/g, '');
    }
    
    // Liste des champs à formater avec leur type de formatage
    const formatRules = {
        // Champs en MAJUSCULES
        'uppercase': ['nom', 'societe', 'ville'],
        // Champs avec première lettre en Majuscule
        'capitalize': ['prenom'],
        // Champs en minuscules
        'lowercase': ['email'],
        // Codes postaux sans espaces
        'postalcode': ['code_postal']
    };
    
    // Application des règles de formatage
    for (const [formatType, fieldIds] of Object.entries(formatRules)) {
        fieldIds.forEach(fieldId => {
            const input = document.getElementById(fieldId);
            if (input) {
                // Appliquer le formatage lorsque l'utilisateur quitte le champ
                input.addEventListener('blur', function() {
                    switch (formatType) {
                        case 'uppercase':
                            toUpperCase(this);
                            break;
                        case 'capitalize':
                            toCapitalize(this);
                            break;
                        case 'lowercase':
                            toLowerCase(this);
                            break;
                        case 'postalcode':
                            cleanPostalCode(this);
                            break;
                    }
                });
            }
        });
    }
});