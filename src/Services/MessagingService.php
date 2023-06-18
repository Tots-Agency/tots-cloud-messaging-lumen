<?php

namespace Tots\CloudMessaging\Services;

class MessagingService
{
    protected $config = [];
    protected $projectId = '';
    protected $apiKey = '';

    public function __construct($config)
    {
        $this->config = $config;
        $this->processConfig();
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
