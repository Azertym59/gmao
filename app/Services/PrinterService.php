<?php

namespace App\Services;

use App\Models\PrintJob;
use App\Models\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class PrinterService
{
    /**
     * Générer un QR code en base64
     *
     * @param string $data Données à encoder
     * @param int $size Taille du QR code
     * @param string $errorCorrection Niveau de correction d'erreur
     * @param array $options Options supplémentaires
     * @return string Image QR code en base64
     */
    public function generateQrCode(string $data, int $size = 150, string $errorCorrection = 'H', array $options = []): string
    {
        try {
            // Génération du QR code avec correction d'erreur élevée
            $qrCode = QrCode::format('png')
                ->size($size)
                ->errorCorrection($errorCorrection);
                
            // Appliquer l'option de marge si fournie
            if (isset($options['margin'])) {
                $qrCode->margin($options['margin']);
            } else {
                // Utiliser des marges plus petites par défaut pour les étiquettes
                $qrCode->margin(1);
            }
            
            // Générer le QR code
            $qrCodeData = $qrCode->generate($data);

            // Convertir en base64
            $base64 = 'data:image/png;base64,' . base64_encode($qrCodeData);
            
            return $base64;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QR code : ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Générer un PDF pour l'impression
     *
     * @param string $htmlContent Contenu HTML à convertir en PDF
     * @param array $options Options de génération du PDF
     * @return string Contenu PDF encodé en base64
     */
    public function generatePdf(string $htmlContent, array $options = []): string
    {
        // Augmenter temporairement la limite de mémoire pour DomPDF
        ini_set('memory_limit', '256M');
        
        try {
            // Options par défaut pour la génération de PDF
            $defaultOptions = config('printing.dompdf.options', [
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'dpi' => 200, // Réduire le DPI pour économiser la mémoire
                'defaultMediaType' => 'print',
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
            ]);
            
            // Fusionner avec les options personnalisées
            $options = array_merge($defaultOptions, $options);
            
            // Dimensions personnalisées si fournies
            $paperSize = $options['paper_size'] ?? 'A4';
            $orientation = $options['orientation'] ?? 'portrait';
            
            // Si des dimensions personnalisées sont fournies, créer un format personnalisé
            if (isset($options['width']) && isset($options['height'])) {
                // Convertir mm en points (1 inch = 25.4 mm, 1 inch = 72 points)
                $width = ($options['width'] / 25.4) * 72;
                $height = ($options['height'] / 25.4) * 72;
                $paperSize = [0, 0, $width, $height];
            }
            
            // Simplifier le HTML si possible pour réduire la consommation de mémoire
            $htmlContent = preg_replace('/\s+/', ' ', $htmlContent);
            
            // Créer le PDF avec DomPDF
            $pdf = PDF::loadHTML($htmlContent)
                ->setPaper($paperSize, $orientation)
                ->setOptions($options);
            
            // Retourner le PDF sous forme de chaîne encodée en base64
            $output = base64_encode($pdf->output());
            
            // Nettoyer la mémoire
            $pdf = null;
            gc_collect_cycles();
            
            return $output;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la génération du PDF: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } finally {
            // Rétablir la limite de mémoire
            ini_set('memory_limit', '128M');
        }
    }

    /**
     * Créer un job d'impression et le mettre en file d'attente
     *
     * @param Printer $printer Imprimante à utiliser
     * @param string $content Contenu à imprimer (HTML ou autre)
     * @param string $type Type de contenu (html, qrcode, template, etc.)
     * @param array $options Options d'impression
     * @return PrintJob Le job d'impression créé
     */
    public function queuePrintJob(Printer $printer, string $content, string $type, array $options = []): PrintJob
    {
        // Options par défaut selon le type d'imprimante
        $defaultOptions = config('printing.brother.default_settings', [
            'width' => '62mm',
            'height' => '80mm',
            'dpi' => '300',
            'rollType' => 'Continuous',
            'paper' => 'Continuous'
        ]);
        
        // Fusionner avec les options fournies
        $options = array_merge($defaultOptions, $options);
        
        // Créer un job d'impression
        $printJob = PrintJob::create([
            'printer_id' => $printer->id,
            'user_id' => Auth::id(),
            'content' => $content,
            'name' => $options['name'] ?? 'Impression ' . date('Y-m-d H:i:s'),
            'content_type' => $type,
            'status' => PrintJob::STATUS_PENDING,
            'options' => $options,
            'attempts' => 0
        ]);
        
        // Journaliser le job d'impression
        Log::info('Job d\'impression mis en file d\'attente', [
            'job_id' => $printJob->id,
            'printer' => $printer->name,
            'type' => $type,
        ]);
        
        return $printJob;
    }

    /**
     * Traiter un job d'impression
     *
     * @param PrintJob $printJob Le job d'impression à traiter
     * @return array Résultat du traitement
     */
    public function processPrintJob(PrintJob $printJob): array
    {
        try {
            $printer = $printJob->printer;
            
            // Mettre à jour le statut du job
            $printJob->markAsPrinting();
            
            // Générer le contenu à imprimer selon le type
            $pdfContent = null;
            
            switch ($printJob->content_type) {
                case 'html':
                    // Générer un PDF à partir du HTML
                    $pdfContent = $this->generatePdf($printJob->content, $printJob->options);
                    break;
                    
                case 'qrcode':
                    // Générer un QR code puis un PDF
                    $qrCode = $this->generateQrCode(
                        $printJob->content,
                        $printJob->options['qr_size'] ?? 300,
                        $printJob->options['qr_error_correction'] ?? 'H'
                    );
                    
                    // Créer un HTML simple pour le QR code
                    $html = '<html><body style="margin: 0; padding: 0; text-align: center;">
                        <img src="' . $qrCode . '" style="max-width: 100%; height: auto;">
                    </body></html>';
                    
                    $pdfContent = $this->generatePdf($html, $printJob->options);
                    break;
                    
                case 'template':
                    // La génération du PDF se fait dans un autre endroit
                    $pdfContent = base64_encode($printJob->content);
                    break;
                    
                default:
                    throw new \Exception("Type de contenu d'impression non pris en charge: " . $printJob->content_type);
            }
            
            // TODO: Implémentation de l'envoi vers l'imprimante Brother QL-820NWB
            // Cela dépendra de la façon dont vous souhaitez interagir avec l'imprimante
            // (via API, serveur d'impression, etc.)
            
            // Pour le moment, simuler une impression réussie
            $result = [
                'success' => true,
                'message' => 'Job traité avec succès (simulation)'
            ];
            
            // Mettre à jour le statut du job
            $printJob->markAsCompleted();
            
            return [
                'success' => true,
                'message' => 'Job d\'impression traité avec succès',
                'job_id' => $printJob->id,
                'result' => $result
            ];
        } catch (\Exception $e) {
            // Journaliser l'erreur
            Log::error('Erreur lors du traitement du job d\'impression', [
                'job_id' => $printJob->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Mettre à jour le statut du job
            $printJob->markAsFailed($e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erreur lors du traitement du job d\'impression: ' . $e->getMessage(),
                'job_id' => $printJob->id
            ];
        }
    }
}