<?php

namespace Tots\CloudMessaging\Services;

use GuzzleHttp\Psr7\Request;
use Google\Auth\Credentials\ServiceAccountCredentials;

class MessagingService
{
    /**
     * URL de la API
     */
    const BASE_URL = 'https://fcm.googleapis.com/v1/projects/';
    const API_CLIENT_SCOPES = [
        'https://www.googleapis.com/auth/cloud-platform',
        'https://www.googleapis.com/auth/firebase',
        'https://www.googleapis.com/auth/firebase.messaging',
    ];

    /**
     *
     * @var array
     */
    protected $config = [];
    /**
     *
     * @var string
     */
    protected $projectId = '';
    /**
     * 
     * @var string
     */
    protected $apiKey = '';
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct($config)
    {
        $this->config = $config;
        $this->processConfig();
        $this->guzzle = new \GuzzleHttp\Client();
    }

    public function sendMessageToDevices($tokens, $title, $body)
    {
        return $this->sendMessageBase([
            'message' => [
                'token' => $tokens,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ]);
    }

    public function sendMessageToSpecificDevive($token, $title, $body)
    {
        return $this->sendMessageBase([
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ]);
    }

    public function sendMessageBase($params = null)
    {
        return $this->generateRequest('POST', '/messages:send', $params);
    }

    protected function generateRequest($method, $path, $params = null)
    {
        $serviceAccount = new ServiceAccountCredentials(self::API_CLIENT_SCOPES, $this->apiKey);
        $authToken = $serviceAccount->fetchAuthToken();

        if(is_array($authToken)){
            $accessToken = $authToken['access_token'];
        } else {
            $accessToken = $authToken;
        }

        $body = null;
        if($params != null){
            $body = json_encode($params);
        }

        $request = new Request(
            $method, 
            self::BASE_URL . $this->projectId . $path, 
            [
                'Content-Type' => 'application/json',
                //'Authorization' => 'key=' . $this->apiKey
                'Authorization' => 'Bearer ' . $accessToken
            ], $body);

        $response = $this->guzzle->send($request);
        if($response->getStatusCode() == 200){
            return json_decode($response->getBody()->getContents());
        }

        return null;
    }

    protected function processConfig()
    {
        if(array_key_exists('project_id', $this->config)){
            $this->projectId = $this->config['project_id'];
        }
        if(array_key_exists('api_key', $this->config)){
            $this->apiKey = $this->config['api_key'];
        }
    }
}
