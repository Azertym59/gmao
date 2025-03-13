@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Impression de test</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Impression en cours...</h5>
                        <p>Un QR code de test est en cours d'impression sur <strong>{{ $printer->name }}</strong>.</p>
                    </div>

                    <!-- QR Code Preview -->
                    <div class="text-center my-4">
                        <h5>Aperçu du QR Code</h5>
                        <img src="{{ $printData['imageData'] }}" alt="QR Code" class="img-fluid" style="max-width: 250px;">
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <h5>Statut QZ Tray</h5>
                        <div id="qz-status" class="badge bg-warning">Vérification...</div>
                        <div id="qz-error" class="alert alert-danger mt-2" style="display: none;"></div>
                    </div>

                    <!-- Success Message -->
                    <div id="print-success" class="alert alert-success mt-4" style="display: none;">
                        Impression envoyée avec succès!
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('printers.test') }}" class="btn btn-primary">Retour au test</a>
                        <button type="button" class="btn btn-success" id="reprint">
                            Réimprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include QZ Tray JavaScript -->
<script src="{{ asset('js/qz-tray.js') }}"></script>

<!-- Print script -->
{!! $qzTrayService->getQzTrayPrintScript($printData) !!}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reprint button
        document.getElementById('reprint').addEventListener('click', function() {
            window.QZTray.printFromController(@json($printData));
        });
    });
</script>
@endsection