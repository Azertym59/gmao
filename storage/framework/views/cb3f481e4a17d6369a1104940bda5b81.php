<?php echo $__env->make("components.module.grid-helper", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de suivi - <?php echo e($chantier->reference); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ChartJS Plugin pour les labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    
    <!-- Styles -->
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --success: #00d624; /* Vert - Terminé */
            --warning: #ffc107; /* Jaune - Stand by */
            --danger: #19918d;  /* Turquoise - Défaillant */
            --hs: #dc3545;      /* Rouge - HS */
            --neutral: #6b7280;
            --bg-dark: #111827;
            --bg-card: #1f2937;
            --text-white: #f9fafb;
            --text-light: #e5e7eb;
            --text-muted: #9ca3af;
            --border: #374151;
            --accent-blue: #60a5fa;
            --accent-green: #34d399;
            --accent-red: #f87171;
            --accent-yellow: #fbbf24;
            --accent-purple: #a78bfa;
            --accent-indigo: #818cf8;
            --accent-pink: #f472b6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Header Section - Style rapport professionnel */
        .header {
            background: linear-gradient(to right, rgba(17, 24, 39, 0.95), rgba(31, 41, 55, 0.95));
            padding: 0;
            border-bottom: 1px solid var(--border);
            position: relative;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .header-top {
            background: linear-gradient(to right, #1E3A8A, #1E40AF);
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header-main {
            padding: 1.5rem 0;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            text-align: center;
        }
        
        .header-logo img {
            height: 5.5rem;
            filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.07)) drop-shadow(0 2px 2px rgba(0, 0, 0, 0.06));
            margin-bottom: 0.75rem;
        }
        
        .welcome-message {
            font-size: 1.125rem;
            color: white;
            margin-top: 0.5rem;
            text-align: center;
            font-weight: 500;
        }
        
        .header-title {
            display: flex;
            flex-direction: column;
        }
        
        .header-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 0.25rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .header-title svg {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--primary-light);
        }
        
        .header-title span {
            font-size: 1rem;
            color: var(--text-light);
            font-weight: 400;
        }
        
        .ref-badge {
            background: linear-gradient(to right, rgba(37, 99, 235, 0.2), rgba(59, 130, 246, 0.2));
            color: var(--primary-light);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.375rem;
            padding: 0.625rem 1rem;
            font-size: 0.9375rem;
            font-weight: 500;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .ref-badge svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary-light);
        }
        
        .print-button {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.375rem;
            padding: 0.625rem;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .print-button:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        /* Main content */
        .main {
            margin-top: 2rem;
            padding-bottom: 2rem;
        }
        
        /* Cards */
        .card {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-header h2 svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-non_commence {
            background-color: rgba(107, 114, 128, 0.1);
            color: var(--text-muted);
            border: 1px solid rgba(107, 114, 128, 0.2);
        }
        
        .status-en_cours {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .status-termine {
            background-color: rgba(0, 214, 36, 0.1);
            color: var(--success);
            border: 1px solid rgba(0, 214, 36, 0.2);
        }
        
        .status-defaillant {
            background-color: rgba(25, 145, 141, 0.1);
            color: var(--danger);
            border: 1px solid rgba(25, 145, 141, 0.2);
        }
        
        .status-stand_by {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        .status-hs {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--hs);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        
        .status-indicator {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .indicator-non_commence {
            background-color: var(--neutral);
        }
        
        .indicator-en_cours {
            background-color: var(--primary);
            animation: pulse 2s infinite;
        }
        
        .indicator-termine {
            background-color: var(--success);
        }
        
        .indicator-defaillant {
            background-color: var(--danger);
        }
        
        .indicator-stand_by {
            background-color: var(--warning);
        }
        
        .indicator-hs {
            background-color: var(--hs);
        }
        
        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-item {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            padding: 1rem;
        }
        
        .info-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .info-value {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Progress Section */
        .progress-section {
            margin-bottom: 1.5rem;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 0.75rem;
        }
        
        .progress-percentage {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        
        .progress-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-left: 0.5rem;
        }
        
        .progress-details {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        
        .progress-bar-container {
            height: 0.5rem;
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-bar {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .progress-success {
            background-color: var(--success);
        }
        
        .progress-warning {
            background-color: var(--warning);
        }
        
        .progress-primary {
            background-color: var(--primary);
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .stat-card {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            width: 4px;
            height: 100%;
            left: 0;
            top: 0;
            background-color: var(--primary);
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Légende des couleurs */
        .color-legend {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1.5rem 0;
        }
        
        .color-legend-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 0.75rem;
            text-align: center;
        }
        
        .color-legend-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
        }
        
        .color-legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .color-dot {
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .color-label {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        /* Charts */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .charts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .chart-container {
            position: relative;
            height: 260px;
            width: 100%;
        }
        
        /* Table */
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-bottom: 1px solid var(--border);
        }
        
        table td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(55, 65, 81, 0.5);
        }
        
        table tr:last-child td {
            border-bottom: none;
        }
        
        table tr:hover td {
            background-color: rgba(55, 65, 81, 0.3);
        }
        
        /* Modules grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .module-card {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        .module-title {
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .module-body {
            padding: 1rem;
        }
        
        .module-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .module-info-item {
            font-size: 0.75rem;
        }
        
        .module-info-label {
            color: var(--text-muted);
            margin-bottom: 0.125rem;
        }
        
        .module-info-value {
            font-weight: 500;
        }
        
        .interventions-list {
            border-top: 1px solid rgba(55, 65, 81, 0.5);
            padding-top: 0.5rem;
            margin-top: 0.75rem;
        }
        
        .intervention-item {
            padding: 0.5rem 0;
            border-bottom: 1px dashed rgba(55, 65, 81, 0.5);
        }
        
        .intervention-item:last-child {
            border-bottom: none;
        }
        
        .intervention-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }
        
        .intervention-title {
            font-weight: 600;
            color: var(--text-light);
        }
        
        .intervention-date {
            color: var(--text-muted);
        }
        
        .intervention-detail {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-info {
                margin-top: 1rem;
                width: 100%;
                justify-content: space-between;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Footer */
        footer {
            border-top: 1px solid var(--border);
            padding: 1.5rem 0;
            margin-top: 2rem;
        }
        
        .footer-content {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        
        /* Collapsible sections */
        .flightcase-header {
            cursor: default;
        }
        
        .flightcase-body {
            max-height: none !important;
            overflow: visible !important;
            transition: none !important;
        }
        
        /* Icônes supprimées */
        
        /* Suppression des rotations d'icônes */
        
        /* FlightCase and Dalle styles - Enhanced 3D effect matching chantier 001 */
        .flightcase-section {
            margin-bottom: 2rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            background-color: rgba(17, 24, 39, 0.4); /* Glassmorphism style */
            backdrop-filter: blur(10px);
        }
        
        .flightcase-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        
        .flightcase-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #60a5fa, #3b82f6); /* accent-blue to primary */
            z-index: 5;
        }
        
        .flightcase-header {
            padding: 1rem 1.25rem;
            background-color: rgba(31, 41, 55, 0.8); /* bg-gray-800/80 */
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .flightcase-title {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            color: #ffffff; /* text-white */
        }
        
        .flightcase-icon {
            color: #60a5fa; /* accent-blue */
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.3));
        }
        
        .flightcase-body {
            padding: 1.25rem;
            background-color: rgba(17, 24, 39, 0.3); /* bg-gray-900/30 */
        }
        
        .dalles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
        }
        
        /* Style pour l'affichage en pleine largeur des dalles individuelles */
        .dalles-full-width {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
        }
        
        .dalle-individuelle {
            width: 100%;
        }
        
        /* Style pour la grille des modules individuels sans flightcase */
        .modules-container-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .individual-module-card {
            background-color: rgba(17, 24, 39, 0.4);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        
        .individual-module-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .individual-module-header {
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .individual-module-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-white);
        }
        
        .individual-module-status {
            font-size: 0.7rem;
            padding: 0.1rem 0.5rem;
            border-radius: 9999px;
            font-weight: 500;
        }
        
        .individual-module-details {
            font-size: 0.7rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }
        
        /* Responsive pour les petits écrans */
        @media (max-width: 640px) {
            .modules-container-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
        
        .dalle-card {
            background-color: rgba(17, 24, 39, 0.4); /* Glassmorphism style */
            backdrop-filter: blur(5px);
            border: 1px solid var(--border);
            border-radius: 0.75rem; /* rounded-xl */
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease-in-out;
            position: relative;
        }
        
        .dalle-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        }
        
        .dalle-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #a78bfa, #818cf8); /* accent-purple to accent-indigo */
            z-index: 5;
        }
        
        .dalle-header {
            padding: 0.85rem 1.15rem;
            background-color: rgba(31, 41, 55, 0.7); /* bg-gray-800/70 */
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .dalle-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff; /* text-white */
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        }
        
        .dalle-body {
            padding: 1rem 1.15rem;
            background-color: rgba(17, 24, 39, 0.2); /* bg-gray-900/20 */
        }
        
        .dalle-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
        }
        
        /* Styles pour les modules avec une présentation simplifiée */
        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        @media (min-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        
        /* Animation de survol pour les indicateurs de module */
        .flex-shrink-0 {
            transition: all 0.2s ease-in-out;
        }
        
        .flex-shrink-0:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        
        /* Ajuster l'affichage pour l'impression */
        @media print {
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .bg-green-500 {
                background-color: #10b981 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .bg-blue-500 {
                background-color: #3b82f6 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .bg-yellow-500 {
                background-color: #f59e0b !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .bg-red-500 {
                background-color: #ef4444 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .bg-gray-500 {
                background-color: #6b7280 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Repair details info */
        .repair-details {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--border);
        }
        
        .repair-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-light);
        }
        
        .repair-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .repair-item {
            background-color: rgba(31, 41, 55, 0.3);
            border-radius: 0.375rem;
            padding: 0.75rem;
            border: 1px solid var(--border);
        }
        
        .repair-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }
        
        .repair-value {
            font-size: 0.9375rem;
            font-weight: 500;
        }
        
        /* Print styles */
        @media print {
            body {
                background-color: white;
                color: black;
            }
            
            .color-legend {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                page-break-inside: avoid;
            }
            
            .color-legend-title {
                color: #111827;
            }
            
            .color-label {
                color: #374151;
            }
            
            .header {
                position: static;
                background-color: white;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 0;
            }
            
            .header-top {
                background-color: #f3f4f6;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .print-button {
                display: none;
            }
            
            .main {
                margin-top: 1rem;
            }
            
            .card {
                background-color: white;
                border: 1px solid #e5e7eb;
                box-shadow: none;
                break-inside: avoid;
            }
            
            .card-header {
                border-bottom: 1px solid #e5e7eb;
            }
            
            .info-item, .stat-card, .module-card, .dalle-card, .flightcase-section {
                background-color: white;
                border: 1px solid #e5e7eb;
            }
            
            .dalle-header, .flightcase-header {
                background-color: #f9fafb;
            }
            
            table td, table th {
                border-color: #e5e7eb;
            }
            
            .status-badge {
                border: 1px solid #e5e7eb;
                color: black;
            }
            
            footer {
                border-top: 1px solid #e5e7eb;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                height: 200px;
            }
            
            .progress-percentage {
                color: #2563eb;
            }
            
            .module-dot {
                border: 1px solid #e5e7eb;
            }
            
            .module-non_commence, .module-null {
                background-color: #9ca3af;
            }
            
            .module-en_cours {
                background-color: #3b82f6;
            }
            
            .module-termine {
                background-color: #10b981;
            }
            
            .module-defaillant {
                background-color: #ef4444;
            }
            
            .module-stand_by {
                background-color: #f59e0b;
            }
        }
    </style>
</head>
<body>
    <!-- Header avec style rapport professionnel -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    <div>
                        <?php if(session()->has('client_id')): ?>
                            <a href="<?php echo e(route('client.dashboard')); ?>" style="color: white; text-decoration: underline;">
                                Retour à mes projets
                            </a>
                        <?php endif; ?>
                    </div>
                    <div>
                        Rapport généré le <?php echo e(now()->format('d/m/Y à H:i')); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <div class="header-logo">
                    <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair%20new.png" alt="TecaLED" style="height: 5.5rem;" />
                    <div class="welcome-message">
                        Bienvenue <?php echo e($chantier->client->civilite ?? 'M./Mme'); ?> <?php echo e($chantier->client->getNomCompletSansDoublonAttribute() ?? $chantier->client->nom_complet); ?>

                    </div>
                </div>
                
                <div class="header-title" style="margin-top: 1.5rem; text-align: center; width: 100%;">
                    <h1 style="justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Rapport de suivi de chantier
                    </h1>
                    <span><?php echo e($chantier->nom); ?></span>
                </div>
                
                <div style="display: flex; justify-content: center; margin-top: 1.25rem; gap: 1.5rem;">
                    <div class="ref-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        Référence: <?php echo e($chantier->reference); ?>

                    </div>
                    <button onclick="window.print()" class="print-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="main">
        <div class="container">
            <!-- Informations du chantier -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informations du chantier
                    </h2>
                    <div class="status-badge status-<?php echo e($chantier->etat); ?>">
                        <span class="status-indicator indicator-<?php echo e($chantier->etat); ?>"></span>
                        <?php if($chantier->etat == 'non_commence'): ?>
                            Non commencé
                        <?php elseif($chantier->etat == 'en_cours'): ?>
                            En cours
                        <?php elseif($chantier->etat == 'termine'): ?>
                            Terminé
                        <?php elseif($chantier->etat == 'stand_by'): ?>
                            En attente
                        <?php elseif($chantier->etat == 'defaillant'): ?>
                            Défaillant
                        <?php else: ?>
                            <?php echo e(ucfirst($chantier->etat)); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Client</div>
                            <div class="info-value"><?php echo e($chantier->client->nom_complet); ?></div>
                            <?php if($chantier->client->societe): ?>
                                <div class="info-value" style="color: var(--text-muted);"><?php echo e($chantier->client->societe); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Référence</div>
                            <div class="info-value"><?php echo e($chantier->reference); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de réception</div>
                            <div class="info-value"><?php echo e($chantier->date_reception->format('d/m/Y')); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de livraison estimée</div>
                            <div class="info-value"><?php echo e($chantier->date_butoir ? $chantier->date_butoir->format('d/m/Y') : 'Non définie'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Dernière mise à jour</div>
                            <div class="info-value"><?php echo e($chantier->updated_at->format('d/m/Y')); ?></div>
                            <div class="info-value" style="color: var(--text-muted);"><?php echo e($chantier->updated_at->format('H:i')); ?></div>
                        </div>
                        <?php if($chantier->description): ?>
                        <div class="info-item">
                            <div class="info-label">Description</div>
                            <div class="info-value"><?php echo e(\Illuminate\Support\Str::limit($chantier->description, 100)); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Informations du produit -->
                    <div style="margin-top: 1.5rem;">
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-light);">
                            Informations du produit
                        </h3>
                        <div class="info-grid">
                            <?php if($chantier->produits->isNotEmpty()): ?>
                                <?php $produit = $chantier->produits->first(); ?>
                                <div class="info-item">
                                    <div class="info-label">Marque</div>
                                    <div class="info-value"><?php echo e($produit->marque ?? 'Non spécifiée'); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Modèle</div>
                                    <div class="info-value"><?php echo e($produit->modele ?? 'Non spécifié'); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Pitch</div>
                                    <div class="info-value"><?php echo e($produit->pitch ?? 'Non spécifié'); ?> mm</div>
                                </div>
                                <?php if($produit->electronique): ?>
                                <div class="info-item">
                                    <div class="info-label">Électronique</div>
                                    <div class="info-value"><?php echo e($produit->electronique); ?></div>
                                    <?php if($produit->electronique_detail): ?>
                                        <div class="info-value" style="color: var(--text-muted);"><?php echo e($produit->electronique_detail); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php if($produit->utilisation): ?>
                                <div class="info-item">
                                    <div class="info-label">Utilisation</div>
                                    <div class="info-value"><?php echo e(ucfirst($produit->utilisation)); ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if($produit->bain_couleur): ?>
                                <div class="info-item">
                                    <div class="info-label">Bain de couleur</div>
                                    <div class="info-value"><?php echo e($produit->bain_couleur); ?></div>
                                </div>
                                <?php endif; ?>
                                <div class="info-item" style="grid-column: 1 / -1;">
                                    <div class="info-label">Garantie</div>
                                    <?php if($chantier->is_under_warranty): ?>
                                        <div class="info-value" style="color: var(--success); display: flex; align-items: center;">
                                            <span style="display: inline-block; width: 32px; height: 32px; margin-right: 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="min-width: 32px; min-height: 32px; max-width: 32px; max-height: 32px;">
                                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </span>
                                            Sous garantie 
                                            <?php if($chantier->warranty_end_date): ?>
                                                jusqu'au <?php echo e($chantier->warranty_end_date->format('d/m/Y')); ?>

                                            <?php endif; ?>
                                            <?php if($chantier->warranty_type): ?>
                                                (<?php echo e($chantier->warranty_type); ?>)
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="info-value" style="color: var(--danger); display: flex; align-items: center;">
                                            <span style="display: inline-block; width: 32px; height: 32px; margin-right: 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="min-width: 32px; min-height: 32px; max-width: 32px; max-height: 32px;">
                                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </span>
                                            Hors garantie
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="info-item">
                                    <div class="info-value" style="color: var(--text-muted);">Aucun produit associé à ce chantier</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avancement du chantier -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Avancement du chantier
                    </h2>
                </div>
                <div class="card-body">
                    <div class="progress-section">
                        <div class="progress-header">
                            <div style="display: flex; align-items: flex-end;">
                                <div class="progress-percentage"><?php echo e($pourcentageTermines); ?>%</div>
                                <div class="progress-label">d'avancement global</div>
                            </div>
                            <div class="progress-details">
                                <?php echo e($modulesTermines); ?> sur <?php echo e($totalModules); ?> modules terminés
                            </div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar 
                                <?php if($pourcentageTermines >= 75): ?>
                                    progress-success
                                <?php elseif($pourcentageTermines >= 40): ?>
                                    progress-primary
                                <?php else: ?>
                                    progress-warning
                                <?php endif; ?>
                                " style="width: <?php echo e($pourcentageTermines); ?>%">
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted);">
                            <span>Démarrage: <?php echo e($dateDebutTravaux); ?></span>
                            <span>Livraison prévue: <?php echo e($dateFinPrevue); ?></span>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card" style="--stat-color: #00d624;">
                            <div class="stat-label">Modules terminés</div>
                            <div class="stat-value" style="color: #00d624;"><?php echo e($modulesTermines); ?></div>
                        </div>
                        <div class="stat-card" style="--stat-color: #3b82f6;">
                            <div class="stat-label">Modules en cours</div>
                            <div class="stat-value" style="color: #3b82f6;"><?php echo e($modulesEnCours); ?></div>
                        </div>
                        <div class="stat-card" style="--stat-color: #ffc107;">
                            <div class="stat-label">Modules en attente</div>
                            <div class="stat-value" style="color: #ffc107;"><?php echo e($modulesStandBy); ?></div>
                        </div>
                        <div class="stat-card" style="--stat-color: #6b7280;">
                            <div class="stat-label">Modules non commencés</div>
                            <div class="stat-value" style="color: #6b7280;"><?php echo e($modulesNonCommences); ?></div>
                        </div>
                        <div class="stat-card" style="--stat-color: #19918d;">
                            <div class="stat-label">Modules défaillants</div>
                            <div class="stat-value" style="color: #19918d;"><?php echo e($modulesDefaillants); ?></div>
                        </div>
                        <div class="stat-card" style="--stat-color: #dc3545;">
                            <div class="stat-label">Modules HS</div>
                            <div class="stat-value" style="color: #dc3545;"><?php echo e($modulesHS ?? 0); ?></div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem;">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Temps total des réparations</div>
                                <div class="info-value"><?php echo e($tempsFormate); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Interventions en cours</div>
                                <div class="info-value"><?php echo e($interventionsEnCours); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dalles au total</div>
                                <div class="info-value">
                                    <?php
                                        $nbDalles = 0;
                                        foreach ($chantier->produits as $produit) {
                                            $nbDalles += $produit->dalles->count();
                                        }
                                        echo $nbDalles;
                                    ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nombre total de modules</div>
                                <div class="info-value"><?php echo e($totalModules); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Graphiques d'analyse -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Analyse du chantier
                    </h2>
                </div>
                <div class="card-body">
                    <div class="charts-grid">
                        <div>
                            <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-light);">
                                État des modules
                            </h3>
                            <div class="chart-container">
                                <canvas id="modulesChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-light);">
                                Composants remplacés
                            </h3>
                            <div class="chart-container">
                                <canvas id="componentsChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-light);">
                                Causes des défaillances
                            </h3>
                            <div class="chart-container">
                                <canvas id="causesChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-light);">
                                Synthèse des réparations
                            </h3>
                            
                            <div class="repair-details">
                                <div class="repair-grid">
                                    <div class="repair-item">
                                        <div class="repair-label">LEDs remplacées</div>
                                        <div class="repair-value"><?php echo e($totalLEDsRemplacees ?? 0); ?></div>
                                    </div>
                                    <div class="repair-item">
                                        <div class="repair-label">ICs remplacés</div>
                                        <div class="repair-value"><?php echo e($totalICsRemplaces ?? 0); ?></div>
                                    </div>
                                    <div class="repair-item">
                                        <div class="repair-label">Masques remplacés</div>
                                        <div class="repair-value"><?php echo e($totalMasquesRemplaces ?? 0); ?></div>
                                    </div>
                                    <div class="repair-item">
                                        <div class="repair-label">Cause principale</div>
                                        <div class="repair-value">
                                            <?php if($causePrincipale == 'usure_normale'): ?>
                                                Usure normale (<?php echo e($pourcentagePrincipal); ?>%)
                                            <?php elseif($causePrincipale == 'choc'): ?>
                                                Choc/Impact (<?php echo e($pourcentagePrincipal); ?>%)
                                            <?php elseif($causePrincipale == 'defaut_usine'): ?>
                                                Défaut usine (<?php echo e($pourcentagePrincipal); ?>%)
                                            <?php else: ?>
                                                Autre (<?php echo e($pourcentagePrincipal); ?>%)
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Détail des dalles et modules -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Flightcases et modules
                    </h2>
                </div>
                <div class="card-body">
                    <!-- Organisation par FlightCase/Dalle/Module -->
                    <?php if($chantier->produits->isNotEmpty()): ?>
                        <?php $__currentLoopData = $chantier->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="margin-bottom: 2rem;">
                                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-light);">
                                    <?php echo e($produit->marque); ?> <?php echo e($produit->modele); ?>

                                </h3>
                                
                                <?php
                                    // Organiser les dalles par flightcase et modules individuels de façon identique à la vue admin
                                    $dallesGrouped = [
                                        'individuel' => [],
                                        'flightcases' => [],
                                        'autres' => []
                                    ];
                                    
                                    // D'abord, grouper les dalles par produit (pour permettre un tri avancé)
                                    $dallesByProducts = [];
                                    foreach($produit->dalles as $dalle) {
                                        $dallesByProducts[] = $dalle;
                                    }

                                    // Traiter chaque dalle avec le même algorithme que la vue admin
                                    foreach($dallesByProducts as $dalle) {
                                        // Cas 1: Dalle individuelle explicite
                                        if($dalle->reference_dalle == "INDIVIDUEL") {
                                            $dallesGrouped['individuel'][] = $dalle;
                                            continue;
                                        }
                                        
                                        // Cas 2: Dalle avec flightcase_number explicite
                                        if(!empty($dalle->flightcase_number)) {
                                            $fcNumber = $dalle->flightcase_number;
                                            if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                                $dallesGrouped['flightcases'][$fcNumber] = [];
                                            }
                                            $dallesGrouped['flightcases'][$fcNumber][] = $dalle;
                                            continue;
                                        }
                                        
                                        // Cas 3: Référence au format FCx-Dx (ex: FC1-D2)
                                        if(preg_match('/^FC(\d+)[- _]?D\d+$/', $dalle->reference_dalle, $matches)) {
                                            $fcNumber = $matches[1];
                                            if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                                $dallesGrouped['flightcases'][$fcNumber] = [];
                                            }
                                            $dallesGrouped['flightcases'][$fcNumber][] = $dalle;
                                            continue;
                                        }
                                        
                                        // Cas 4: Référence contenant FCx n'importe où
                                        if(preg_match('/FC[- _]?(\d+)/', $dalle->reference_dalle, $matches)) {
                                            $fcNumber = $matches[1];
                                            if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                                $dallesGrouped['flightcases'][$fcNumber] = [];
                                            }
                                            $dallesGrouped['flightcases'][$fcNumber][] = $dalle;
                                            continue;
                                        }
                                        
                                        // Cas 5: Numéro explicite de FC sans préfixe
                                        if(preg_match('/^(\d+)[- _](FC|FLIGHTCASE|FLIGHT)/i', $dalle->reference_dalle, $matches)) {
                                            $fcNumber = $matches[1];
                                            if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                                $dallesGrouped['flightcases'][$fcNumber] = [];
                                            }
                                            $dallesGrouped['flightcases'][$fcNumber][] = $dalle;
                                            continue;
                                        }
                                        
                                        // Cas 6: Par défaut, si tous les regroupements échouent
                                        $dallesGrouped['autres'][] = $dalle;
                                    }
                                    
                                    // Trier les flightcases par numéro
                                    ksort($dallesGrouped['flightcases']);
                                ?>
                                
                                <?php if(!empty($dallesGrouped['individuel']) || !empty($dallesGrouped['flightcases']) || !empty($dallesGrouped['autres'])): ?>
                                    <!-- Légende des couleurs -->
                                    <div class="color-legend">
                                        <h4 class="color-legend-title">Légende des états des modules</h4>
                                        <div class="color-legend-items">
                                            <div class="color-legend-item">
                                                <span class="color-dot" style="background-color: #00d624;"></span>
                                                <span class="color-label">Terminé</span>
                                            </div>
                                            <div class="color-legend-item">
                                                <span class="color-dot" style="background-color: #3b82f6;"></span>
                                                <span class="color-label">En cours</span>
                                            </div>
                                            <div class="color-legend-item">
                                                <span class="color-dot" style="background-color: #ffc107;"></span>
                                                <span class="color-label">En attente</span>
                                            </div>
                                            <div class="color-legend-item">
                                                <span class="color-dot" style="background-color: #19918d;"></span>
                                                <span class="color-label">Défaillant</span>
                                            </div>
                                            <div class="color-legend-item">
                                                <span class="color-dot" style="background-color: #dc3545;"></span>
                                                <span class="color-label">Hors service</span>
                                            </div>
                                            <div class="color-legend-item">
                                                <span class="color-dot" style="background-color: #6b7280;"></span>
                                                <span class="color-label">Non commencé</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modules individuels -->
                                    <?php if(!empty($dallesGrouped['individuel'])): ?>
                                        <div class="flightcase-section">
                                            <div class="flightcase-header">
                                                <div class="flightcase-title">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="flightcase-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                    </svg>
                                                    Modules individuels
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                        <?php echo e(count($dallesGrouped['individuel'])); ?> dalle(s)
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="individuel-<?php echo e($produit->id); ?>" class="flightcase-body flightcase-body ">
                                                <div class="dalles-full-width">
                                                    <?php $__currentLoopData = $dallesGrouped['individuel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="dalle-card dalle-individuelle">
                                                            <div class="dalle-header">
                                                                <div class="dalle-title">
                                                                    Dalle <?php echo e($dalle->reference_dalle ?? $dalle->id); ?>

                                                                    <?php if($dalle->numero_dalle): ?>
                                                                        <span style="font-size: 0.75rem; color: var(--accent-blue);">[N° <?php echo e($dalle->numero_dalle); ?>]</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="status-badge status-<?php echo e($dalle->etat); ?>">
                                                                    <span class="status-indicator indicator-<?php echo e($dalle->etat); ?>"></span>
                                                                    <?php if($dalle->etat == 'non_commence'): ?>
                                                                        Non commencée
                                                                    <?php elseif($dalle->etat == 'en_cours'): ?>
                                                                        En cours
                                                                    <?php elseif($dalle->etat == 'termine'): ?>
                                                                        Terminée
                                                                    <?php elseif($dalle->etat == 'stand_by'): ?>
                                                                        En attente
                                                                    <?php elseif($dalle->etat == 'defaillant'): ?>
                                                                        Défaillante
                                                                    <?php elseif($dalle->etat == 'hs'): ?>
                                                                        Hors service
                                                                    <?php else: ?>
                                                                        <?php echo e(ucfirst($dalle->etat)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="dalle-body">
                                                                <!-- Utiliser l'implémentation spécifique pour les modules individuels -->
                                                                <?php echo $__env->make('suivi.partials.dalle-content-modules-individuels', ['dalle' => $dalle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- FlightCases -->
                                    <?php $__currentLoopData = $dallesGrouped['flightcases']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fcNumber => $dalles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flightcase-section">
                                            <div class="flightcase-header">
                                                <div class="flightcase-title">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="flightcase-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                    </svg>
                                                    FlightCase #<?php echo e($fcNumber); ?>

                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                        <?php echo e(count($dalles)); ?> dalle(s)
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="fc-<?php echo e($produit->id); ?>-<?php echo e($fcNumber); ?>" class="flightcase-body flightcase-body ">
                                                <div class="dalles-grid">
                                                    <?php $__currentLoopData = $dalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="dalle-card">
                                                            <div class="dalle-header">
                                                                <div class="dalle-title">
                                                                    Dalle <?php echo e($dalle->reference_dalle ?? $dalle->id); ?>

                                                                    <?php if($dalle->numero_dalle): ?>
                                                                        <span style="font-size: 0.75rem; color: var(--accent-blue);">[N° <?php echo e($dalle->numero_dalle); ?>]</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="status-badge status-<?php echo e($dalle->etat); ?>">
                                                                    <span class="status-indicator indicator-<?php echo e($dalle->etat); ?>"></span>
                                                                    <?php if($dalle->etat == 'non_commence'): ?>
                                                                        Non commencée
                                                                    <?php elseif($dalle->etat == 'en_cours'): ?>
                                                                        En cours
                                                                    <?php elseif($dalle->etat == 'termine'): ?>
                                                                        Terminée
                                                                    <?php elseif($dalle->etat == 'stand_by'): ?>
                                                                        En attente
                                                                    <?php elseif($dalle->etat == 'defaillant'): ?>
                                                                        Défaillante
                                                                    <?php else: ?>
                                                                        <?php echo e(ucfirst($dalle->etat)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="dalle-body">
                                                                <!-- Dalle Content - Uses the same implementation from above -->
                                                                <?php echo $__env->make('suivi.partials.dalle-content', ['dalle' => $dalle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <!-- Autres dalles -->
                                    <?php if(!empty($dallesGrouped['autres'])): ?>
                                        <div class="flightcase-section">
                                            <div class="flightcase-header">
                                                <div class="flightcase-title">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="flightcase-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                    </svg>
                                                    Dalles diverses
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                        <?php echo e(count($dallesGrouped['autres'])); ?> dalle(s)
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="autres-<?php echo e($produit->id); ?>" class="flightcase-body flightcase-body ">
                                                <div class="dalles-grid">
                                                    <?php $__currentLoopData = $dallesGrouped['autres']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="dalle-card">
                                                            <div class="dalle-header">
                                                                <div class="dalle-title">
                                                                    Dalle <?php echo e($dalle->reference_dalle ?? $dalle->id); ?>

                                                                    <?php if($dalle->numero_dalle): ?>
                                                                        <span style="font-size: 0.75rem; color: var(--accent-blue);">[N° <?php echo e($dalle->numero_dalle); ?>]</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="status-badge status-<?php echo e($dalle->etat); ?>">
                                                                    <span class="status-indicator indicator-<?php echo e($dalle->etat); ?>"></span>
                                                                    <?php if($dalle->etat == 'non_commence'): ?>
                                                                        Non commencée
                                                                    <?php elseif($dalle->etat == 'en_cours'): ?>
                                                                        En cours
                                                                    <?php elseif($dalle->etat == 'termine'): ?>
                                                                        Terminée
                                                                    <?php elseif($dalle->etat == 'stand_by'): ?>
                                                                        En attente
                                                                    <?php elseif($dalle->etat == 'defaillant'): ?>
                                                                        Défaillante
                                                                    <?php else: ?>
                                                                        <?php echo e(ucfirst($dalle->etat)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="dalle-body">
                                                                <!-- Dalle Content - Uses the same implementation from above -->
                                                                <?php echo $__env->make('suivi.partials.dalle-content', ['dalle' => $dalle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Special case for backwards compatibility -->
                                    <?php if(empty($dallesGrouped['individuel']) && empty($dallesGrouped['flightcases']) && empty($dallesGrouped['autres'])): ?>
                                        <!-- No dalles found in our new structure - use legacy approach -->
                                        <?php
                                            $legacyDallesGrouped = [];
                                            foreach($produit->dalles as $dalle) {
                                                $fcNumber = $dalle->flightcase_number ?? 'Individuel';
                                                if(!isset($legacyDallesGrouped[$fcNumber])) {
                                                    $legacyDallesGrouped[$fcNumber] = [];
                                                }
                                                $legacyDallesGrouped[$fcNumber][] = $dalle;
                                            }
                                            ksort($legacyDallesGrouped);
                                        ?>
                                        
                                        <?php $__currentLoopData = $legacyDallesGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fcNumber => $dalles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flightcase-section">
                                                <div class="flightcase-header">
                                                    <div class="flightcase-title">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="flightcase-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                        </svg>
                                                        <?php if($fcNumber == 'Individuel'): ?>
                                                            Modules individuels
                                                        <?php else: ?>
                                                            FlightCase #<?php echo e($fcNumber); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                            <?php echo e(count($dalles)); ?> dalle(s)
                                                        </div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="collapsible-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="6 9 12 15 18 9"></polyline>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div id="legacy-<?php echo e($produit->id); ?>-<?php echo e($fcNumber); ?>" class="flightcase-body flightcase-body ">
                                                    <div class="dalles-grid">
                                                        <?php $__currentLoopData = $dalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="dalle-card">
                                                                <div class="dalle-header">
                                                                    <div class="dalle-title">
                                                                        Dalle <?php echo e($dalle->reference_dalle ?? $dalle->id); ?>

                                                                        <?php if($dalle->numero_dalle): ?>
                                                                            <span style="font-size: 0.75rem; color: var(--accent-blue);">[N° <?php echo e($dalle->numero_dalle); ?>]</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="status-badge status-<?php echo e($dalle->etat); ?>">
                                                                        <span class="status-indicator indicator-<?php echo e($dalle->etat); ?>"></span>
                                                                        <?php if($dalle->etat == 'non_commence'): ?>
                                                                            Non commencée
                                                                        <?php elseif($dalle->etat == 'en_cours'): ?>
                                                                            En cours
                                                                        <?php elseif($dalle->etat == 'termine'): ?>
                                                                            Terminée
                                                                        <?php elseif($dalle->etat == 'stand_by'): ?>
                                                                            En attente
                                                                        <?php elseif($dalle->etat == 'defaillant'): ?>
                                                                            Défaillante
                                                                        <?php else: ?>
                                                                            <?php echo e(ucfirst($dalle->etat)); ?>

                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="dalle-body">
                                                                    <?php echo $__env->make('suivi.partials.dalle-content', ['dalle' => $dalle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                        Aucune dalle trouvée pour ce produit
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </main>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <p>Rapport généré le <?php echo e(now()->format('d/m/Y à H:i')); ?> par TecaLED</p>
                <p>Ce document est confidentiel et réservé à l'usage exclusif du client</p>
            </div>
        </div>
    </footer>
    
    <!-- Module Details Modal -->
    <div id="moduleDetailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background-color: var(--bg-card); border-radius: 0.5rem; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; padding: 1.5rem; border: 1px solid var(--border); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">
                <h3 id="moduleTitle" style="font-size: 1.125rem; font-weight: 600;"></h3>
                <button onclick="closeModuleDetails()" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <div id="moduleDetails" style="margin-bottom: 1.5rem;">
                <div class="info-grid" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="info-item">
                        <div class="info-label">Référence</div>
                        <div id="moduleReference" class="info-value"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Position</div>
                        <div id="modulePosition" class="info-value"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Dalle</div>
                        <div id="moduleDalle" class="info-value"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">État</div>
                        <div id="moduleStatus" class="info-value"></div>
                    </div>
                </div>
            </div>
            
            <div id="moduleInterventions">
                <h4 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-light);">
                    Interventions
                </h4>
                <div id="interventionsLoading" style="display: flex; justify-content: center; padding: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div id="interventionsList">
                    <!-- Les interventions seront injectées ici via JavaScript -->
                </div>
                <div id="noInterventions" style="display: none; text-align: center; padding: 1rem; color: var(--text-muted);">
                    Aucune intervention n'a encore été effectuée sur ce module.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Définir les couleurs pour les graphiques
        const colors = {
            blue: '#3b82f6',
            green: '#10b981',
            red: '#ef4444',
            yellow: '#f59e0b',
            gray: '#6b7280',
            purple: '#a78bfa',
            indigo: '#818cf8',
            pink: '#f472b6'
        };
        
        // Les sections sont maintenant toujours visibles
        
        // Fonction pour afficher les détails d'un module
        function showModuleDetails(moduleId, reference, etat, dalle, position) {
            // Mettre à jour les informations dans le modal
            document.getElementById('moduleTitle').textContent = 'Module ' + reference;
            document.getElementById('moduleReference').textContent = reference;
            document.getElementById('modulePosition').textContent = position;
            document.getElementById('moduleDalle').textContent = 'Dalle ' + dalle;
            
            // Mettre à jour le statut avec la couleur appropriée
            const statusElement = document.getElementById('moduleStatus');
            statusElement.textContent = formatStatus(etat);
            statusElement.className = 'info-value';
            
            if (etat === 'termine') {
                statusElement.style.color = '#00d624'; // Vert - Terminé
            } else if (etat === 'en_cours') {
                statusElement.style.color = '#3b82f6'; // Bleu - En cours
            } else if (etat === 'stand_by') {
                statusElement.style.color = '#ffc107'; // Jaune - En attente
            } else if (etat === 'defaillant') {
                statusElement.style.color = '#19918d'; // Turquoise - Défaillant
            } else if (etat === 'hs') {
                statusElement.style.color = '#dc3545'; // Rouge - HS
            } else {
                statusElement.style.color = '#6b7280'; // Gris - Non commencé
            }
            
            // Réinitialiser la section des interventions
            document.getElementById('interventionsLoading').style.display = 'flex';
            document.getElementById('interventionsList').innerHTML = '';
            document.getElementById('noInterventions').style.display = 'none';
            
            // Afficher le modal
            const modal = document.getElementById('moduleDetailsModal');
            modal.style.display = 'flex';
            
            // Simuler le chargement des interventions (dans une vraie application, vous feriez un appel API)
            // Pour l'instant, nous allons juste afficher un message "aucune intervention"
            setTimeout(() => {
                document.getElementById('interventionsLoading').style.display = 'none';
                document.getElementById('noInterventions').style.display = 'block';
            }, 1000);
        }
        
        // Fonction pour fermer le modal
        function closeModuleDetails() {
            document.getElementById('moduleDetailsModal').style.display = 'none';
        }
        
        // Fonction pour formater l'état
        function formatStatus(etat) {
            switch(etat) {
                case 'non_commence': return 'Non commencé';
                case 'en_cours': return 'En cours';
                case 'termine': return 'Terminé';
                case 'stand_by': return 'En attente';
                case 'defaillant': return 'Défaillant';
                case 'hs': return 'Hors Service';
                default: return etat ? etat.charAt(0).toUpperCase() + etat.slice(1) : 'Inconnu';
            }
        }
        
        // Initialiser les graphiques après le chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Enregistrer le plugin datalabels globalement
            Chart.register(ChartDataLabels);
            
            // Graphique de l'état des modules
            const modulesCtx = document.getElementById('modulesChart').getContext('2d');
            const modulesChart = new Chart(modulesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Terminés', 'En cours', 'En attente', 'Non commencés', 'Défaillants', 'HS'],
                    datasets: [{
                        data: [<?php echo e($modulesTermines); ?>, <?php echo e($modulesEnCours); ?>, <?php echo e($modulesStandBy); ?>, <?php echo e($modulesNonCommences); ?>, <?php echo e($modulesDefaillants); ?>, <?php echo e($modulesHS ?? 0); ?>],
                        backgroundColor: ['#00d624', '#3b82f6', '#ffc107', '#6b7280', '#19918d', '#dc3545'],
                        borderColor: '#1f2937',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e5e7eb',
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
            
            // Graphique des composants remplacés
            const componentsCtx = document.getElementById('componentsChart').getContext('2d');
            const componentsChart = new Chart(componentsCtx, {
                type: 'pie',
                data: {
                    labels: ['LEDs', 'ICs', 'Masques'],
                    datasets: [{
                        data: [<?php echo e($totalLEDsRemplacees ?? 0); ?>, <?php echo e($totalICsRemplaces ?? 0); ?>, <?php echo e($totalMasquesRemplaces ?? 0); ?>],
                        backgroundColor: [colors.purple, colors.indigo, colors.pink],
                        borderColor: '#1f2937',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e5e7eb',
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return value > 0 ? value : '';
                            }
                        }
                    }
                }
            });
            
            // Graphique des causes
            const causesCtx = document.getElementById('causesChart').getContext('2d');
            const causesChart = new Chart(causesCtx, {
                type: 'bar',
                data: {
                    labels: ['Usure normale', 'Choc/Impact', 'Défaut usine', 'Autre'],
                    datasets: [{
                        data: [<?php echo e($causes['usure_normale']); ?>, <?php echo e($causes['choc']); ?>, <?php echo e($causes['defaut_usine']); ?>, <?php echo e($causes['autre']); ?>],
                        backgroundColor: [colors.blue, colors.yellow, colors.red, colors.gray],
                        borderColor: '#1f2937',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1
                        },
                        datalabels: {
                            color: 'white',
                            anchor: 'end',
                            align: 'top',
                            offset: 4,
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(55, 65, 81, 0.3)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/suivi/chantier.blade.php ENDPATH**/ ?>