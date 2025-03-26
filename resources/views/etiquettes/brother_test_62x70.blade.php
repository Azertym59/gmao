<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Étiquette Brother 62x80mm</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Style pour la prévisualisation de l'étiquette */
        .label-preview {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            width: 62mm;
            height: 80mm;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background-color: white;
        }
        .container-preview {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            padding: 2mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .header {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            width: 100%;
            margin-bottom: 2mm;
        }
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            width: 100%;
        }
        .qrcode {
            width: 24mm;
            height: 24mm;
            margin: 0 auto;
            display: block;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            width: 100%;
            margin-top: 2mm;
        }
        .info {
            text-align: center;
            font-size: 9pt;
            margin: 2mm 0;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Page principale avec Tailwind -->
    <div class="p-6 max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Test d'impression Brother QL-820NWB - Format 62×80mm (DK-22205)</h1>
        
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Prévisualisation de l'étiquette -->
            <div class="flex-1 mb-6">
                <h2 class="text-lg font-semibold mb-3 text-gray-700">Prévisualisation</h2>
                <div class="label-preview mx-auto">
                    <div class="container-preview">
                        <div class="header">
                            TECALED - TEST IMPRESSION
                        </div>
                        
                        <div class="content">
                            <img src="{{ $qrcode }}" class="qrcode" alt="QR Code de test">
                            <div class="info">
                                <p><strong>Date:</strong> {{ date('d/m/Y H:i') }}</p>
                                <p><strong>Format:</strong> 62mm × 80mm (DK-22205)</p>
                            </div>
                        </div>
                        
                        <div class="footer">
                            Test d'impression Brother QL-820NWB
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panneau d'options d'impression -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold mb-3 text-gray-700">Options d'impression</h2>
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    <div class="mb-4" id="printerSelector">
                        <label for="printer" class="block text-sm font-medium text-gray-700 mb-1">Imprimante</label>
                        <select id="printer" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Sélectionnez une imprimante...</option>
                            <!-- Les imprimantes seront chargées dynamiquement -->
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" id="realPrintMode" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Impression réelle (envoyer à l'imprimante)</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="format_type" id="formatNormal" value="normal" checked class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Format normal</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="format_type" id="formatAlt" value="alt" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Utiliser format alternatif (22205 uniquement)</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="format_type" id="formatRaw" value="raw" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Mode texte brut</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="format_type" id="formatStandard" value="standard" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Mode standard Brother (DK-22205)</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="format_type" id="formatMinimal" value="minimal" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Mode minimal (paramètres réduits)</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="format_type" id="formatDirect" value="direct" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 font-bold">Mode direct USB (contourne PrintNode)</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label for="paperType" class="block text-sm font-medium text-gray-700 mb-1">Type de papier manuel:</label>
                        <select id="paperType" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Sélectionnez un type ou entrez manuellement</option>
                            <option value="dk-22205">dk-22205 (Ruban continu 62mm)</option>
                            <option value="22205">22205 (Sans préfixe)</option>
                            <option value="DK-22205">DK-22205 (Majuscules)</option>
                            <option value="continuous">continuous (Générique)</option>
                            <option value="continuous-roll">continuous-roll (Rouleau continu)</option>
                            <option value="continuous-label">continuous-label (Étiquette continue)</option>
                            <option value="brother_continuous">brother_continuous (Format Brother)</option>
                            <option value="Custom.62x100mm">Custom.62x100mm (Format personnalisé)</option>
                            <option value="claude_method">Méthode Claude (paperWidth/paperLength)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Vous pouvez aussi saisir un autre code dans ce champ.</p>
                    </div>
                    
                    <div class="mb-4">
                        <a href="#" id="diagnosticLink" class="text-xs text-blue-600 hover:text-blue-800">
                            Voir diagnostic d'impression
                        </a>
                        <div id="diagnosticInfo" class="hidden mt-2 text-xs p-2 bg-gray-100 rounded border border-gray-300 overflow-auto" style="max-height: 200px;">
                            Chargement des informations...
                        </div>
                    </div>
                    
                    <button id="printButton" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Imprimer l'étiquette
                    </button>
                    
                    <div id="printStatus" class="mt-3 text-sm hidden">
                        <div class="p-3 rounded-md text-center" id="statusContent"></div>
                    </div>
                </div>
                
                <!-- Indicateur d'erreur -->
                <div id="errorMessage" class="hidden mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded"></div>
                
                <!-- Aperçu PDF -->
                <div id="pdfPreview" class="hidden mt-4">
                    <h3 class="text-md font-medium mb-2 text-gray-700">Aperçu PDF</h3>
                    <div class="border border-gray-300 rounded-md p-3 bg-gray-50">
                        <iframe id="pdfFrame" class="w-full h-64 border-0"></iframe>
                    </div>
                    <a id="pdfDownload" href="#" class="inline-flex items-center mt-2 text-sm text-indigo-600 hover:text-indigo-500">
                        Télécharger le PDF
                        <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les éléments
            const printButton = document.getElementById('printButton');
            const printerSelect = document.getElementById('printer');
            const printStatus = document.getElementById('printStatus');
            const statusContent = document.getElementById('statusContent');
            const errorMessage = document.getElementById('errorMessage');
            const pdfPreview = document.getElementById('pdfPreview');
            const pdfFrame = document.getElementById('pdfFrame');
            const pdfDownload = document.getElementById('pdfDownload');
            
            // Fonction pour charger les imprimantes
            async function loadPrinters() {
                try {
                    const response = await fetch('/api/printers');
                    const data = await response.json();
                    
                    if (data.success) {
                        data.printers.forEach(printer => {
                            const option = document.createElement('option');
                            option.value = printer.id;
                            option.textContent = printer.name;
                            printerSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des imprimantes:', error);
                    const option = document.createElement('option');
                    option.value = "test";
                    option.textContent = "Mode test (pas d'imprimante)";
                    printerSelect.appendChild(option);
                }
            }
            
            // Chargement des imprimantes au démarrage
            loadPrinters().catch(e => console.error(e));
            
            // Gestionnaire pour le lien de diagnostic
            const diagnosticLink = document.getElementById('diagnosticLink');
            const diagnosticInfo = document.getElementById('diagnosticInfo');
            
            diagnosticLink.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (diagnosticInfo.classList.contains('hidden')) {
                    try {
                        diagnosticInfo.textContent = "Chargement des informations...";
                        diagnosticInfo.classList.remove('hidden');
                        
                        const response = await fetch('/api/print/diagnostic');
                        if (!response.ok) {
                            throw new Error(`Erreur HTTP: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        // Formater les informations de diagnostic
                        let diagnosticText = `== Configuration PrintNode ==\n`;
                        diagnosticText += `API Key: ${data.printnode.api_key_configured ? "Configurée (" + data.printnode.api_key_masked + ")" : "Non configurée"}\n`;
                        diagnosticText += `Printer ID: ${data.printnode.printer_id_configured ? data.printnode.printer_id : "Non configuré ou invalide"}\n\n`;
                        
                        // Ajouter informations ezeep si disponibles
                        if (data.ezeep) {
                            diagnosticText += `== Configuration ezeep ==\n`;
                            diagnosticText += `Client ID: ${data.ezeep.client_id_configured ? "Configuré (" + data.ezeep.client_id_masked + ")" : "Non configuré"}\n`;
                            diagnosticText += `Client Secret: ${data.ezeep.client_secret_configured ? "Configuré (" + data.ezeep.client_secret_masked + ")" : "Non configuré"}\n`;
                            diagnosticText += `Printer ID: ${data.ezeep.printer_id_configured ? data.ezeep.printer_id : "Non configuré"}\n\n`;
                        }
                        
                        diagnosticText += `== Imprimantes locales ==\n`;
                        if (data.local_printers.length === 0) {
                            diagnosticText += "Aucune imprimante configurée\n";
                        } else {
                            data.local_printers.forEach(printer => {
                                diagnosticText += `- ${printer.name} (ID: ${printer.id})\n`;
                                diagnosticText += `  Type: ${printer.type}\n`;
                                diagnosticText += `  Default: ${printer.is_default ? "Oui" : "Non"}\n`;
                                
                                // Afficher PrintNode ID ou ezeep ID selon le type d'imprimante
                                if (printer.type === 'printnode') {
                                    diagnosticText += `  PrintNode ID: ${printer.printnode_id || "Non configuré"}\n`;
                                } else if (printer.type === 'ezeep') {
                                    diagnosticText += `  ezeep ID: ${printer.ezeep_id || "Non configuré"}\n`;
                                }
                                diagnosticText += `\n`;
                            });
                        }
                        
                        diagnosticInfo.textContent = diagnosticText;
                    } catch (error) {
                        diagnosticInfo.textContent = `Erreur lors du chargement du diagnostic: ${error.message}`;
                    }
                } else {
                    diagnosticInfo.classList.add('hidden');
                }
            });
            
            // Fonction pour imprimer
            printButton.addEventListener('click', async function() {
                // Réinitialiser les messages d'erreur et d'état
                errorMessage.classList.add('hidden');
                printStatus.classList.remove('hidden');
                statusContent.classList.remove('bg-green-100', 'bg-red-100', 'bg-yellow-100');
                statusContent.classList.add('bg-yellow-100');
                statusContent.innerHTML = 'Envoi de la demande d\'impression...';
                
                try {
                    console.log("Envoi de la demande d'impression...");
                    const response = await fetch('/api/print/test-label', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            printer_id: printerSelect.value || null,
                            print_mode: document.getElementById('realPrintMode').checked ? 'real' : 'test',
                            format_alt: document.querySelector('input[name="format_type"]:checked').value === 'normal' ? 
                                        false : document.querySelector('input[name="format_type"]:checked').value,
                            paper_type: document.getElementById('paperType').value || null
                        })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status} ${response.statusText}`);
                    }
                    
                    // Vérifie si la réponse contient du contenu
                    const text = await response.text();
                    if (!text) {
                        throw new Error("Réponse vide du serveur");
                    }
                    
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error("Erreur lors du parsing JSON:", e);
                        console.log("Réponse brute:", text);
                        throw new Error("Réponse invalide du serveur (JSON invalide)");
                    }
                    
                    if (data.success) {
                        statusContent.classList.remove('bg-yellow-100');
                        statusContent.classList.add('bg-green-100');
                        statusContent.innerHTML = 'Succès! ' + data.message;
                        
                        // Si un PDF est retourné
                        if (data.pdf_base64) {
                            try {
                                // Vérifier que pdf_base64 est bien une chaîne qui commence par 'data:'
                                if (typeof data.pdf_base64 === 'string' && data.pdf_base64.startsWith('data:')) {
                                    pdfPreview.classList.remove('hidden');
                                    pdfFrame.src = data.pdf_base64;
                                    
                                    // Configurer le bouton de téléchargement selon le type
                                    if (data.pdf_base64.startsWith('data:text/html')) {
                                        pdfDownload.style.display = 'none'; // Masquer le bouton de téléchargement
                                    } else {
                                        pdfDownload.href = data.pdf_base64;
                                        pdfDownload.download = 'etiquette-test-' + new Date().getTime() + '.pdf';
                                        pdfDownload.style.display = 'inline-flex';
                                    }
                                    console.log("PDF/HTML chargé avec succès");
                                } else {
                                    // Erreur : la réponse n'est pas au bon format
                                    console.error("Format de pdf_base64 invalide:", typeof data.pdf_base64);
                                    console.error("Premiers caractères:", data.pdf_base64 ? data.pdf_base64.substring(0, 50) : "null");
                                    
                                    // Créer un HTML avec un message d'erreur
                                    const errorHtmlBase64 = 'data:text/html;base64,' + btoa('<html><body><div style="color:red;text-align:center;padding:20px;"><h3>Erreur d\'affichage</h3><p>Format de réponse invalide pour l\'aperçu.</p><p><small>Valeur reçue: "' + (typeof data.pdf_base64) + '"</small></p></div></body></html>');
                                    
                                    pdfPreview.classList.remove('hidden');
                                    pdfFrame.src = errorHtmlBase64;
                                    pdfDownload.style.display = 'none';
                                }
                            } catch (err) {
                                console.error("Erreur lors du chargement du PDF:", err);
                                statusContent.innerHTML += '<br><small>PDF généré mais l\'aperçu n\'a pas pu être affiché</small>';
                            }
                        } else {
                            console.warn("Aucun PDF n'a été retourné dans la réponse");
                        }
                    } else {
                        statusContent.classList.remove('bg-yellow-100');
                        statusContent.classList.add('bg-red-100');
                        statusContent.innerHTML = 'Erreur: ' + data.message;
                    }
                } catch (error) {
                    console.error('Erreur lors de l\'impression:', error);
                    statusContent.classList.remove('bg-yellow-100');
                    statusContent.classList.add('bg-red-100');
                    statusContent.innerHTML = 'Erreur technique: ' + error.message;
                    
                    errorMessage.classList.remove('hidden');
                    errorMessage.textContent = 'Une erreur est survenue lors de la communication avec le serveur.';
                }
            });
        });
    </script>
</body>
</html>