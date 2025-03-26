<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperçu du modèle d'étiquette</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .preview-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .preview-label {
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .info-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #1a202c;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            z-index: 100;
        }
        .close-button {
            background-color: #4a5568;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .close-button:hover {
            background-color: #2d3748;
        }
        .print-button {
            background-color: #ecc94b;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
            transition: background-color 0.2s;
        }
        .print-button:hover {
            background-color: #d69e2e;
        }
        .actions {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="info-bar">
        <div>
            <strong>{{ $template->name }}</strong> ({{ $template->width }}mm x {{ $template->height }}mm)
        </div>
        <div class="actions">
            <form action="{{ route('admin.etiquettes.test-print', $template) }}" method="POST" target="_blank">
                @csrf
                <button type="submit" class="print-button">Imprimer</button>
            </form>
            <button class="close-button" onclick="window.close()">Fermer</button>
        </div>
    </div>
    
    <div class="preview-container">
        <div class="preview-label" id="labelPreview" style="width: {{ $template->width * 4 }}px; height: {{ $template->height * 4 }}px;">
            {!! $renderedTemplate !!}
        </div>
    </div>
</body>
</html>