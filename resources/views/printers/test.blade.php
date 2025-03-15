@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Test QZ Tray</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Info:</strong> Assurez-vous que QZ Tray est installé et en cours d'exécution sur votre ordinateur.
                    </div>

                    <!-- QZ Tray Status -->
                    <div class="mb-4">
                        <h5>Statut QZ Tray</h5>
                        <div id="qz-status" class="badge bg-warning">Vérification...</div>
                        <div id="qz-error" class="alert alert-danger mt-2" style="display: none;"></div>
                        <button id="connect-qz" class="btn btn-primary btn-sm mt-2">
                            Connecter à QZ Tray
                        </button>
                    </div>

                    <!-- Printer Selection Form -->
                    <form method="POST" action="{{ route('printers.print-test') }}" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="printer_id" class="form-label">Sélectionner une imprimante</label>
                            <select class="form-control" id="printer_id" name="printer_id" required>
                                <option value="">Sélectionner...</option>
                                @foreach($printers as $printer)
                                    <option value="{{ $printer->id }}" {{ isset($selectedPrinter) && $selectedPrinter && $selectedPrinter->id == $printer->id ? 'selected' : '' }}>
                                        {{ $printer->name }} ({{ $printer->type }})
                                        @if($printer->is_default) [Par défaut] @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" id="print-test">
                            Imprimer QR Code de test
                        </button>
                    </form>

                    <!-- Printers List from QZ Tray -->
                    <div class="mt-4">
                        <h5>Imprimantes détectées par QZ Tray</h5>
                        <div id="printer-list" class="list-group">
                            <div class="text-center p-2">Chargement en cours...</div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div id="print-success" class="alert alert-success mt-4" style="display: none;">
                        Impression envoyée avec succès!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/qz-tray.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Connect QZ Tray button
        document.getElementById('connect-qz').addEventListener('click', function() {
            window.QZTray.connect();
        });

        // Get printers from QZ Tray after connection
        setTimeout(function() {
            const printerListElem = document.getElementById('printer-list');
            
            window.QZTray.getPrinters()
                .then(function(printers) {
                    if (printers.length === 0) {
                        printerListElem.innerHTML = '<div class="alert alert-warning">Aucune imprimante détectée</div>';
                        return;
                    }
                    
                    printerListElem.innerHTML = '';
                    printers.forEach(function(printer) {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = printer;
                        item.onclick = function(e) {
                            e.preventDefault();
                            // Auto-select this printer in the dropdown
                            const printerSelect = document.getElementById('printer_id');
                            
                            // Find by name
                            for(let i = 0; i < printerSelect.options.length; i++) {
                                const option = printerSelect.options[i];
                                if (option.text.includes(printer)) {
                                    printerSelect.value = option.value;
                                    break;
                                }
                            }
                        };
                        printerListElem.appendChild(item);
                    });
                })
                .catch(function(error) {
                    printerListElem.innerHTML = '<div class="alert alert-danger">Erreur: ' + error + '</div>';
                });
        }, 2000);
    });
</script>
@endsection