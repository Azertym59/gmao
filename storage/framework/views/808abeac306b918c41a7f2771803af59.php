<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étiquettes Flightcases - <?php echo e($chantier->reference); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .controls {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .controls button {
            padding: 8px 15px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .controls button:hover {
            background-color: #2563eb;
        }
        .flightcases-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .flightcase-item {
            /* Dimensions pour l'affichage à l'écran */
            width: 62mm;
            height: 90mm;
            border: 1px solid #ddd;
            page-break-inside: avoid;
            position: relative;
            box-sizing: border-box;
        }
        
        /* Styles de l'étiquette (copié du template flightcase.blade.php) */
        .etiquette {
            width: 62mm;
            height: 90mm;
            padding: 2mm;
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
        }
        .header {
            text-align: center;
            padding-bottom: 1mm;
            border-bottom: 1pt solid #000;
            margin-bottom: 2mm;
        }
        .logo {
            height: 8mm;
            margin-bottom: 1mm;
        }
        .company {
            font-size: 10pt;
            font-weight: bold;
            margin: 0;
        }
        .reference {
            font-size: 12pt;
            font-weight: bold;
            color: #cc0000;
            margin: 1mm 0;
        }
        .client {
            font-size: 10pt;
            margin: 1mm 0;
        }
        .flightcase-info {
            border: 1pt solid #000;
            padding: 1mm;
            margin: 2mm 0;
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
        }
        .qrcode-container {
            text-align: center;
            margin: 3mm 0;
        }
        .qrcode {
            width: 28mm;
            height: 28mm;
        }
        .remarque {
            border: 1pt solid #000;
            padding: 1mm;
            margin-top: 2mm;
            min-height: 15mm;
        }
        .remarque-label {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }
        .dalles-info {
            font-size: 9pt;
            margin: 2mm 0;
        }
        .footer {
            position: absolute;
            bottom: 2mm;
            left: 2mm;
            right: 2mm;
            font-size: 7pt;
            text-align: center;
            color: #666;
        }
        
        /* Styles pour l'impression */
        @media print {
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
            .controls {
                display: none;
            }
            .flightcases-container {
                display: block;
            }
            .flightcase-item {
                width: 62mm;
                height: 90mm;
                margin: 0;
                padding: 0;
                border: none;
                page-break-after: always;
            }
            .flightcase-item:last-child {
                page-break-after: avoid;
            }
            .no-print { 
                display: none; 
            }
        }
    </style>
</head>
<body>
    <div class="controls no-print">
        <h1>Étiquettes Flightcases - <?php echo e($chantier->reference); ?></h1>
        <p>Chantier: <strong><?php echo e($chantier->reference); ?></strong> | Client: <strong><?php echo e($chantier->client->societe); ?></strong></p>
        <p>Nombre total de flightcases: <strong><?php echo e(count($flightcases)); ?></strong></p>
        <button onclick="printAllFlightcases()">Imprimer les étiquettes via PrintNode</button>
        <button onclick="window.print()">Aperçu d'impression</button>
        <button onclick="downloadAllPDFs()">Télécharger les PDFs</button>
        <button onclick="window.history.back()">Retour</button>
    </div>
    
    <div class="flightcases-container">
        <?php $__currentLoopData = $flightcases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flightcase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flightcase-item">
            <div class="etiquette">
                <div class="header">
                    <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair%20new.png" alt="TecaLED" class="logo">
                    <p class="company">TECALED GMAO</p>
                </div>
                
                <p class="reference"><?php echo e($flightcase['chantier']->reference); ?></p>
                <p class="client"><?php echo e($flightcase['chantier']->client->societe); ?></p>
                
                <div class="flightcase-info">
                    FLIGHTCASE <?php echo e($flightcase['flightcaseNumber']); ?>/<?php echo e($flightcase['totalFlightcases']); ?>

                </div>
                
                <p class="dalles-info">Contient <?php echo e($flightcase['nbDalles']); ?> dalle(s)</p>
                
                <div class="qrcode-container">
                    <img src="<?php echo e($flightcase['qrCode']); ?>" alt="QR Code" class="qrcode">
                </div>
                
                <div class="remarque">
                    <div class="remarque-label">REMARQUES:</div>
                </div>
                
                <div class="footer">
                    Scan QR code pour accès au chantier - <?php echo e(date('d/m/Y')); ?>

                </div>
                
                <!-- Bouton d'impression directe (visible seulement à l'écran) -->
                <div class="no-print absolute top-2 right-2">
                    <button 
                        onclick="printSingleFlightcase(<?php echo e($flightcase['chantier']->id); ?>, <?php echo e($flightcase['flightcaseNumber']); ?>)"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs rounded px-2 py-1 shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <script>
        // Auto-print quand la page est prête
        document.addEventListener('DOMContentLoaded', function() {
            // Si on veut que l'impression se déclenche automatiquement
            // window.print();
        });
        
        // Fonction pour télécharger tous les PDFs
        function downloadAllPDFs() {
            const flightcases = [];
            
            <?php $__currentLoopData = $flightcases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flightcase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            flightcases.push({
                chantierId: <?php echo e($flightcase['chantier']->id); ?>,
                flightcaseNumber: <?php echo e($flightcase['flightcaseNumber']); ?>

            });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            // Télécharger chaque PDF dans un nouvel onglet
            flightcases.forEach(function(flightcase) {
                const pdfUrl = `/chantiers/${flightcase.chantierId}/flightcase-etiquettes/${flightcase.flightcaseNumber}/download`;
                window.open(pdfUrl, '_blank');
            });
        }
        
        // Fonction pour imprimer toutes les étiquettes via PrintNode
        function printAllFlightcases() {
            // Afficher un indicateur de chargement
            const loadingIndicator = document.createElement('div');
            loadingIndicator.style.position = 'fixed';
            loadingIndicator.style.top = '50%';
            loadingIndicator.style.left = '50%';
            loadingIndicator.style.transform = 'translate(-50%, -50%)';
            loadingIndicator.style.padding = '20px';
            loadingIndicator.style.backgroundColor = 'rgba(0,0,0,0.8)';
            loadingIndicator.style.color = 'white';
            loadingIndicator.style.borderRadius = '10px';
            loadingIndicator.style.zIndex = '9999';
            loadingIndicator.style.fontWeight = 'bold';
            loadingIndicator.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" class="animate-pulse" style="display: inline-block; margin-bottom: 10px;" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg><div>Envoi de toutes les étiquettes vers l\'imprimante...</div></div>';
            document.body.appendChild(loadingIndicator);
            
            // Récupérer les flightcases à imprimer
            const flightcases = [];
            
            <?php $__currentLoopData = $flightcases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flightcase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            flightcases.push({
                chantierId: <?php echo e($flightcase['chantier']->id); ?>,
                flightcaseNumber: <?php echo e($flightcase['flightcaseNumber']); ?>

            });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            // Variables pour suivi
            const totalFlightcases = flightcases.length;
            let successCount = 0;
            let errorCount = 0;
            let currentIndex = 0;
            let successMessages = [];
            let errorMessages = [];
            
            // Mise à jour de l'indicateur de chargement
            function updateLoadingIndicator() {
                loadingIndicator.innerHTML = `
                    <div style="text-align: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="animate-pulse" style="display: inline-block; margin-bottom: 10px;" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        <div>
                            Impression des étiquettes... ${currentIndex}/${totalFlightcases}
                            <div style="font-size: 0.8em; margin-top: 5px;">
                                <span style="color: #4ade80;">${successCount} succès</span> | 
                                <span style="color: #f87171;">${errorCount} erreurs</span>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Fonction pour traiter les flightcases en séquence
            function processFlightcases() {
                if (currentIndex >= flightcases.length) {
                    // Terminé, afficher le résultat
                    showResults();
                    return;
                }
                
                // Récupérer le flightcase courant
                const flightcase = flightcases[currentIndex];
                
                // Appeler l'API pour imprimer
                fetch(`/chantiers/${flightcase.chantierId}/flightcase-etiquettes/${flightcase.flightcaseNumber}/print`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successCount++;
                        successMessages.push(`Flightcase ${flightcase.flightcaseNumber}: Impression réussie`);
                    } else {
                        errorCount++;
                        errorMessages.push(`Flightcase ${flightcase.flightcaseNumber}: ${data.message}`);
                    }
                })
                .catch(error => {
                    errorCount++;
                    errorMessages.push(`Flightcase ${flightcase.flightcaseNumber}: Erreur réseau - ${error.message}`);
                })
                .finally(() => {
                    currentIndex++;
                    updateLoadingIndicator();
                    
                    // Traiter le prochain flightcase
                    setTimeout(processFlightcases, 500); // Intervalle de 500ms entre chaque impression
                });
            }
            
            // Fonction pour afficher les résultats finaux
            function showResults() {
                // Supprimer l'indicateur de chargement
                document.body.removeChild(loadingIndicator);
                
                // Créer le dialogue de résultat
                const resultBox = document.createElement('div');
                resultBox.style.position = 'fixed';
                resultBox.style.top = '50%';
                resultBox.style.left = '50%';
                resultBox.style.transform = 'translate(-50%, -50%)';
                resultBox.style.padding = '20px';
                resultBox.style.borderRadius = '10px';
                resultBox.style.zIndex = '9999';
                resultBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                resultBox.style.maxWidth = '80%';
                resultBox.style.maxHeight = '80%';
                resultBox.style.overflowY = 'auto';
                
                if (errorCount === 0) {
                    // Tout s'est bien passé
                    resultBox.style.backgroundColor = 'rgba(46, 125, 50, 0.9)';
                    resultBox.style.color = 'white';
                    
                    resultBox.innerHTML = `
                        <div style="text-align: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>Toutes les étiquettes (${totalFlightcases}) ont été envoyées avec succès</div>
                        </div>
                    `;
                } else if (successCount === 0) {
                    // Tout a échoué
                    resultBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                    resultBox.style.color = 'white';
                    
                    let errorHtml = `
                        <div style="text-align: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>Échec de l'impression de toutes les étiquettes (${totalFlightcases})</div>
                            <ul style="text-align: left; margin-top: 10px;">
                    `;
                    
                    errorMessages.forEach(msg => {
                        errorHtml += `<li>${msg}</li>`;
                    });
                    
                    errorHtml += `
                            </ul>
                        </div>
                    `;
                    
                    resultBox.innerHTML = errorHtml;
                } else {
                    // Résultats mixtes
                    resultBox.style.backgroundColor = 'rgba(245, 158, 11, 0.9)';
                    resultBox.style.color = 'white';
                    
                    let mixedHtml = `
                        <div style="text-align: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>Impression partielle: ${successCount} réussies, ${errorCount} échecs</div>
                    `;
                    
                    if (successMessages.length > 0) {
                        mixedHtml += `
                            <div style="text-align: left; margin-top: 10px; padding: 5px; background-color: rgba(46, 125, 50, 0.3); border-radius: 5px;">
                                <strong>Succès:</strong>
                                <ul>
                        `;
                        
                        successMessages.forEach(msg => {
                            mixedHtml += `<li>${msg}</li>`;
                        });
                        
                        mixedHtml += `
                                </ul>
                            </div>
                        `;
                    }
                    
                    if (errorMessages.length > 0) {
                        mixedHtml += `
                            <div style="text-align: left; margin-top: 10px; padding: 5px; background-color: rgba(198, 40, 40, 0.3); border-radius: 5px;">
                                <strong>Erreurs:</strong>
                                <ul>
                        `;
                        
                        errorMessages.forEach(msg => {
                            mixedHtml += `<li>${msg}</li>`;
                        });
                        
                        mixedHtml += `
                                </ul>
                            </div>
                        `;
                    }
                    
                    mixedHtml += `</div>`;
                    
                    resultBox.innerHTML = mixedHtml;
                }
                
                document.body.appendChild(resultBox);
                
                // Supprimer après 10 secondes
                setTimeout(() => {
                    document.body.removeChild(resultBox);
                }, 10000);
            }
            
            // Démarrer le traitement
            updateLoadingIndicator();
            processFlightcases();
        }
        
        // Fonction pour imprimer une étiquette de flightcase spécifique
        function printSingleFlightcase(chantierId, flightcaseNumber) {
            // Debug log dans la console
            console.log(`Impression de l'étiquette: Chantier #${chantierId}, Flightcase #${flightcaseNumber}`);
            
            // Afficher un indicateur de chargement
            const loadingIndicator = document.createElement('div');
            loadingIndicator.style.position = 'fixed';
            loadingIndicator.style.top = '50%';
            loadingIndicator.style.left = '50%';
            loadingIndicator.style.transform = 'translate(-50%, -50%)';
            loadingIndicator.style.padding = '20px';
            loadingIndicator.style.backgroundColor = 'rgba(0,0,0,0.8)';
            loadingIndicator.style.color = 'white';
            loadingIndicator.style.borderRadius = '10px';
            loadingIndicator.style.zIndex = '9999';
            loadingIndicator.style.fontWeight = 'bold';
            loadingIndicator.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" class="animate-pulse" style="display: inline-block; margin-bottom: 10px;" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg><div>Envoi vers l\'imprimante...</div></div>';
            document.body.appendChild(loadingIndicator);
            
            // Envoyer la requête à l'API
            const url = `/chantiers/${chantierId}/flightcase-etiquettes/${flightcaseNumber}/print`;
            console.log(`Envoi de la requête vers: ${url}`);
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Supprimer l'indicateur de chargement
                document.body.removeChild(loadingIndicator);
                
                // Afficher le message de succès ou d'erreur
                const messageBox = document.createElement('div');
                messageBox.style.position = 'fixed';
                messageBox.style.top = '50%';
                messageBox.style.left = '50%';
                messageBox.style.transform = 'translate(-50%, -50%)';
                messageBox.style.padding = '20px';
                messageBox.style.borderRadius = '10px';
                messageBox.style.zIndex = '9999';
                messageBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                
                if (data.success) {
                    messageBox.style.backgroundColor = 'rgba(46, 125, 50, 0.9)';
                    messageBox.style.color = 'white';
                    messageBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><div>Étiquette envoyée avec succès à l\'imprimante ' + data.printer + '</div></div>';
                } else {
                    messageBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                    messageBox.style.color = 'white';
                    messageBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg><div>Erreur: ' + data.message + '</div></div>';
                }
                
                document.body.appendChild(messageBox);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => {
                    document.body.removeChild(messageBox);
                }, 5000);
            })
            .catch(error => {
                // Supprimer l'indicateur de chargement
                document.body.removeChild(loadingIndicator);
                
                // Afficher le message d'erreur
                const errorBox = document.createElement('div');
                errorBox.style.position = 'fixed';
                errorBox.style.top = '50%';
                errorBox.style.left = '50%';
                errorBox.style.transform = 'translate(-50%, -50%)';
                errorBox.style.padding = '20px';
                errorBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                errorBox.style.color = 'white';
                errorBox.style.borderRadius = '10px';
                errorBox.style.zIndex = '9999';
                errorBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                errorBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg><div>Erreur de connexion: ' + error.message + '</div></div>';
                
                document.body.appendChild(errorBox);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => {
                    document.body.removeChild(errorBox);
                }, 5000);
            });
        }
    </script>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/etiquettes/flightcase_batch.blade.php ENDPATH**/ ?>