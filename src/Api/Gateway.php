<?php

namespace Djam90\Harvest\Api;

use Exception;
use GuzzleHttp\Client;

class Gateway
{
    protected $apiClient;

    /**
     * Gateway constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->uri = config('harvest.uri');
        $this->token = config('harvest.personal_access_token');
        $this->account_id = config('harvest.account_id');

        if (! $this->hasCredentials()) {
            throw new Exception('Credentials not found, please ensure you published the package config file with artisan vendor:publish');
        }

        $apiClient = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Harvest-Account-Id' => $this->account_id,
                'User-Agent' => 'Harvest API App'
            ]
        ]);

        $this->apiClient = $apiClient;
    }

    public function getWithoutBase($uri, $data = null)
    {
        $response = $this->apiClient->request('GET', $uri);

        return $this->transformResponse($response);

    }

    public function get($uri, $data = null)
    {
        $response = $this->apiClient->request('GET', $this->uri . $uri, [
            'json' => $data
        ]);

        return $this->transformResponse($response);
    }

    public function post($uri, $data = null)
    {
        $response = $this->apiClient->request('POST', $this->uri . $uri, [
            'json' => $data
        ]);

        return $this->transformResponse($response);
    }

    public function patch($uri, $data = null)
    {
        $response = $this->apiClient->request('PATCH', $this->uri . $uri, [
            'json' => $data
        ]);

        return $this->transformResponse($response);
    }

    public function delete($uri)
    {
        $response = $this->apiClient->delete($uri);

        return $this->transformResponse($response);
    }

    private function hasCredentials()
    {
        return !is_null($this->uri) &&
            !is_null($this->token) &&
            !is_null($this->account_id);
    }

    public function transformResponse($response)
    {
        return (new Response($response))->toObject();
    }
}