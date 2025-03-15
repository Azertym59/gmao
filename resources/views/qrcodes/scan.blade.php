@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Scanner un QR Code</h1>
        
        <div class="flex justify-center mb-6">
            <div id="reader" style="width: 100%; max-width: 500px;"></div>
        </div>
        
        <div class="text-center">
            <p id="result" class="mb-4 text-lg font-semibold">En attente de scan...</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            document.getElementById('result').textContent = "QR Code détecté, redirection...";
            html5QrCode.stop();
            window.location.href = decodedText;
        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        // Commencer à scanner
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    });
</script>
@endpush