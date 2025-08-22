<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class CampayService
{
    private $client;
    private $baseUrl;
    private $appId;
    private $appSecret;
    private $username;
    private $password;
    private $environment;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.campay.api_url');
        $this->appId = config('services.campay.app_id');
        $this->appSecret = config('services.campay.app_secret');
        $this->username = config('services.campay.username');
        $this->password = config('services.campay.password');
        $this->environment = config('services.campay.environment');
    }

    /**
     * Obtenir le token d'authentification
     */
    private function getAuthToken()
    {
        try {
            $response = $this->client->post($this->baseUrl . '/token/', [
                'json' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['token'] ?? null;
        } catch (GuzzleException $e) {
            Log::error('Campay Auth Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Initier un paiement
     */
    public function initiatePayment($amount, $phoneNumber, $description, $externalId = null)
    {
        $token = $this->getAuthToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = $this->client->post($this->baseUrl . '/collect/', [
                'headers' => [
                    'Authorization' => 'Token ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'amount' => $amount,
                    'currency' => 'XAF',
                    'from' => $phoneNumber,
                    'description' => $description,
                    'external_id' => $externalId ?: uniqid('campay_'),
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            Log::info('Campay Payment Initiated', [
                'external_id' => $externalId,
                'amount' => $amount,
                'phone' => $phoneNumber,
                'response' => $data
            ]);

            return [
                'success' => true,
                'reference' => $data['reference'] ?? null,
                'ussd_code' => $data['ussd_code'] ?? null,
                'operator' => $data['operator'] ?? null,
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            Log::error('Campay Payment Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function getPaymentStatus($reference)
    {
        $token = $this->getAuthToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = $this->client->get($this->baseUrl . '/transaction/' . $reference . '/', [
                'headers' => [
                    'Authorization' => 'Token ' . $token,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            return [
                'success' => true,
                'status' => $data['status'] ?? 'PENDING',
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            Log::error('Campay Status Check Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Obtenir l'historique des transactions
     */
    public function getTransactions($limit = 100)
    {
        $token = $this->getAuthToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = $this->client->get($this->baseUrl . '/transaction/', [
                'headers' => [
                    'Authorization' => 'Token ' . $token,
                ],
                'query' => [
                    'limit' => $limit
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            return [
                'success' => true,
                'transactions' => $data['results'] ?? [],
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            Log::error('Campay Transactions Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
