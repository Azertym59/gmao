@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Impression de test</div>

                <div class="card-body">
                    <!-- Status Panel -->
                    <div class="print-status mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Statut de l'impression</h5>
                            <div id="qz-status" class="badge bg-warning">Connexion en cours...</div>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div id="print-progress" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 25%"></div>
                        </div>
                        <div id="qz-error" class="alert alert-danger mt-2" style="display: none;"></div>
                        <div id="print-success" class="alert alert-success mt-2" style="display: none;">
                            Impression envoyée avec succès!
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Printer Details -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Imprimante</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Nom</th>
                                            <td>{{ $printer->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{ ucfirst($printer->type) }}</td>
                                        </tr>
                                        @if($printer->model)
                                        <tr>
                                            <th>Modèle</th>
                                            <td>{{ $printer->model }}</td>
                                        </tr>
                                        @endif
                                        @if($printer->dpi)
                                        <tr>
                                            <th>DPI</th>
                                            <td>{{ $printer->dpi }}</td>
                                        </tr>
                                        @endif
                                        @if($printer->label_width && $printer->label_height)
                                        <tr>
                                            <th>Dimensions</th>
                                            <td>{{ $printer->label_width }} x {{ $printer->label_height }} mm</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- QR Code Preview -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Aperçu du QR Code</h5>
                                </div>
                                <div class="card-body text-center py-4">
                                    <img src="{{ $printData['imageData'] }}" alt="QR Code" class="img-fluid" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('printers.test') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour au test
                        </a>
                        <div>
                            <button type="button" class="btn btn-warning me-2" id="detect-printers">
                                <i class="fas fa-sync-alt mr-1"></i> Détecter imprimantes
                            </button>
                            <button type="button" class="btn btn-success" id="reprint">
                                <i class="fas fa-print mr-1"></i> Réimprimer
                            </button>
                        </div>
                    </div>
                    
                    <!-- Printer List from QZ Tray -->
                    <div class="mt-4">
                        <h5>Imprimantes détectées par QZ Tray</h5>
                        <div id="printer-list" class="list-group">
                            <div class="text-center p-2">Chargement en cours...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! $qzTrayService->getQzTrayScripts() !!}
{!! $qzTrayService->getQzTrayPrintScript($printData) !!}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const printData = @json($printData);
        const progressBar = document.getElementById('print-progress');
        
        // Function to update print progress indication
        function updatePrintProgress(status, percentage) {
            if (progressBar) {
                progressBar.style.width = percentage + '%';
                progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-' + 
                    (status === 'error' ? 'danger' : 
                     status === 'success' ? 'success' : 'info');
            }
        }
        
        // Function to refresh printer list
        function refreshPrinterList() {
            const printerListElem = document.getElementById('printer-list');
            
            // Show loading message
            if (printerListElem) {
                printerListElem.innerHTML = '<div class="text-center p-2">Chargement des imprimantes...</div>';
            }
            
            // Update status
            const statusElem = document.getElementById('qz-status');
            if (statusElem) {
                statusElem.className = 'badge bg-info';
                statusElem.textContent = 'Recherche d\'imprimantes...';
            }
            
            updatePrintProgress('info', 50);
            
            // Call QZ Tray to get printer list
            if (typeof window.QZTray === 'undefined') {
                if (printerListElem) {
                    printerListElem.innerHTML = '<div class="alert alert-warning">QZ Tray non disponible</div>';
                }
                return;
            }
            
            window.QZTray.connect()
                .then(() => window.QZTray.getPrinters())
                .then(printers => {
                    if (!printerListElem) return;
                    
                    if (printers.length === 0) {
                        printerListElem.innerHTML = '<div class="alert alert-warning">Aucune imprimante détectée</div>';
                        return;
                    }
                    
                    // Highlight the current printer
                    const currentPrinter = '{{ $printer->name }}';
                    
                    // Create list of printers
                    printerListElem.innerHTML = '';
                    printers.forEach(printer => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                        if (printer === currentPrinter) {
                            item.classList.add('active');
                        }
                        
                        // Create printer name with icon
                        const nameSpan = document.createElement('span');
                        nameSpan.innerHTML = `<i class="fas fa-print mr-2"></i> ${printer}`;
                        item.appendChild(nameSpan);
                        
                        // Add badge if it's the current printer
                        if (printer === currentPrinter) {
                            const badge = document.createElement('span');
                            badge.className = 'badge bg-light text-dark';
                            badge.textContent = 'Sélectionnée';
                            item.appendChild(badge);
                        }
                        
                        // Add click handler to print to this printer
                        item.onclick = function(e) {
                            e.preventDefault();
                            
                            // Create a modified copy of the print data with the new printer
                            const newPrintData = JSON.parse(JSON.stringify(printData));
                            newPrintData.printerName = printer;
                            
                            // Update status
                            if (statusElem) {
                                statusElem.className = 'badge bg-info';
                                statusElem.textContent = 'Impression vers ' + printer;
                            }
                            
                            // Hide previous error
                            const errorElem = document.getElementById('qz-error');
                            if (errorElem) {
                                errorElem.style.display = 'none';
                            }
                            
                            updatePrintProgress('info', 25);
                            
                            // Print to the selected printer
                            window.QZTray.printFromController(newPrintData)
                                .then(() => {
                                    // Show success message
                                    const successElem = document.getElementById('print-success');
                                    if (successElem) {
                                        successElem.textContent = 'Impression réussie sur ' + printer;
                                        successElem.style.display = 'block';
                                    }
                                    
                                    if (statusElem) {
                                        statusElem.className = 'badge bg-success';
                                        statusElem.textContent = 'Impression réussie';
                                    }
                                    
                                    updatePrintProgress('success', 100);
                                })
                                .catch(error => {
                                    console.error('Error printing to selected printer:', error);
                                    
                                    if (errorElem) {
                                        errorElem.textContent = 'Erreur lors de l\'impression vers ' + printer + ': ' + 
                                            (error.message || error);
                                        errorElem.style.display = 'block';
                                    }
                                    
                                    if (statusElem) {
                                        statusElem.className = 'badge bg-danger';
                                        statusElem.textContent = 'Erreur d\'impression';
                                    }
                                    
                                    updatePrintProgress('error', 100);
                                });
                        };
                        
                        printerListElem.appendChild(item);
                    });
                    
                    // Update status
                    if (statusElem) {
                        statusElem.className = 'badge bg-success';
                        statusElem.textContent = printers.length + ' imprimantes détectées';
                    }
                })
                .catch(error => {
                    console.error('Error getting printers:', error);
                    
                    if (printerListElem) {
                        printerListElem.innerHTML = 
                            `<div class="alert alert-danger">Erreur: ${error.message || error}</div>`;
                    }
                    
                    // Update status
                    if (statusElem) {
                        statusElem.className = 'badge bg-danger';
                        statusElem.textContent = 'Erreur de détection';
                    }
                    
                    updatePrintProgress('error', 100);
                });
        }
        
        // Refresh printer list when clicking the button
        const detectPrintersBtn = document.getElementById('detect-printers');
        if (detectPrintersBtn) {
            detectPrintersBtn.addEventListener('click', refreshPrinterList);
        }
        
        // Refresh printer list initially after a delay
        setTimeout(refreshPrinterList, 2000);
    });
</script>
@endsection