<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EzeepService
{
    protected $apiUrl;
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;
    protected $tokenExpires;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->apiUrl = config('services.ezeep.api_url', 'https://api.ezeep.com');
        $this->clientId = config('services.ezeep.client_id');
        $this->clientSecret = config('services.ezeep.client_secret');
        $this->accessToken = null;
        $this->tokenExpires = 0;
        
        // Log de débogage pour vérifier les informations de configuration
        Log::debug('Initialisation EzeepService', [
            'api_url' => $this->apiUrl,
            'client_id_set' => !empty($this->clientId),
            'client_secret_set' => !empty($this->clientSecret)
        ]);
    }

    /**
     * Obtenir un token d'accès OAuth2
     *
     * @return string Token d'accès
     * @throws Exception Si l'authentification échoue
     */
    public function getAccessToken(): string
    {
        // Vérifier si nous avons déjà un token valide
        if ($this->accessToken && time() < $this->tokenExpires - 60) {
            return $this->accessToken;
        }

        try {
            // Vérifier si les identifiants sont configurés
            if (empty($this->clientId) || empty($this->clientSecret)) {
                throw new Exception('Identifiants ezeep non configurés (client_id ou client_secret manquant)');
            }

            // Log pour debug
            Log::info('Tentative d\'authentification ezeep', [
                'api_url' => $this->apiUrl,
                'client_id_set' => !empty($this->clientId),
                'client_secret_set' => !empty($this->clientSecret)
            ]);
            
            // Demander un nouveau token
            $response = Http::asForm()->post($this->apiUrl . '/oauth/access_token/', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];
                $this->tokenExpires = time() + $data['expires_in'];
                return $this->accessToken;
            } else {
                Log::error('Échec de l\'authentification ezeep', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                throw new Exception('Échec de l\'authentification ezeep: ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'authentification ezeep', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Liste des imprimantes disponibles
     *
     * @return array Liste des imprimantes
     * @throws Exception Si la requête échoue
     */
    public function getPrinters(): array
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/sfapi/v1/printers');

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Échec de la récupération des imprimantes ezeep', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                throw new Exception('Échec de la récupération des imprimantes: ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des imprimantes ezeep', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Envoyer un document à l'impression
     *
     * @param string $printerId ID de l'imprimante ezeep
     * @param string $fileContent Contenu du fichier à imprimer (en base64)
     * @param string $fileName Nom du fichier à imprimer
     * @param array $options Options d'impression
     * @return array Réponse de l'API
     * @throws Exception Si la requête échoue
     */
    public function printDocument(string $printerId, string $fileContent, string $fileName, array $options = []): array
    {
        try {
            $token = $this->getAccessToken();
            
            // Préparer les options d'impression par défaut
            $defaultOptions = [
                'color' => false,
                'duplex' => false,
                'copies' => 1,
                'collate' => true,
                'orientation' => 'portrait',
                // Utiliser un format prédéfini au lieu de custom
                'paper_size' => 'Label.29x90mm', 
                'fit_to_page' => true,
            ];
            
            // Fusionner avec les options fournies
            $printOptions = array_merge($defaultOptions, $options);
            
            // Log des options pour le débogage
            Log::info('Options d\'impression ezeep', $printOptions);
            
            // Préparer le corps de la requête
            $requestBody = [
                'printer_id' => $printerId,
                'file_content' => $fileContent,
                'file_name' => $fileName,
                'options' => $printOptions,
            ];
            
            $response = Http::withToken($token)
                ->post($this->apiUrl . '/sfapi/v1/print', $requestBody);

            if ($response->successful()) {
                $printJob = $response->json();
                
                Log::info('Document envoyé à ezeep avec succès', [
                    'job_id' => $printJob['job_id'] ?? 'inconnu',
                    'printer' => $printerId,
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Document envoyé à l\'impression',
                    'job_id' => $printJob['job_id'] ?? null,
                    'status' => $printJob['status'] ?? 'pending',
                ];
            } else {
                Log::error('Échec de l\'impression avec ezeep', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Échec de l\'impression: ' . $response->status(),
                    'error' => $response->body(),
                ];
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'impression avec ezeep', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'impression: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Récupérer l'état d'un job d'impression
     *
     * @param string $jobId ID du job d'impression
     * @return array État du job
     */
    public function getJobStatus(string $jobId): array
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/sfapi/v1/jobs/' . $jobId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'job' => $response->json(),
                ];
            } else {
                Log::error('Échec de la récupération du statut du job ezeep', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Échec de la récupération du statut: ' . $response->status(),
                ];
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération du statut du job ezeep', [
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ];
        }
    }
}