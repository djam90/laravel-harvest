<?php

namespace Djam90\Harvest;

use GuzzleHttp\Client;

class BaseService
{
    /**
     * @var Client $client
     */
    protected $client;
    protected $uri;
    protected $token;
    protected $account_id;

    public function __construct()
    {
        $this->uri = config('harvest.uri');
        $this->token = config('harvest.personal_access_token');
        $this->account_id = config('harvest.account_id');

        $client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Harvest-Account-Id' => $this->account_id,
                'User-Agent' => 'Harvest API App'
            ]
        ]);

        $this->client = $client;
    }

    public function toArray($json)
    {
        return json_decode($json, true);
    }

    public function httpGet($uri, $data = null)
    {
        $res = $this->client->request('GET', $this->uri . $uri, [
            'json' => $data
        ]);

        return $this->toArray(
            (string)$res->getBody()
        );
    }

    public function httpPost($uri, $data = null)
    {
        $res = $this->client->request('POST', $this->uri . $uri, [
            'json' => $data
        ]);

        return $this->toArray(
            (string)$res->getBody()
        );
    }

    public function patch($uri, $data = null)
    {
        $res = $this->client->request('PATCH', $this->uri . $uri, [
            'json' => $data
        ]);

        return $this->toArray(
            (string)$res->getBody()
        );
    }

    public function httpDelete($uri)
    {
        $res = $this->client->delete($uri);

        return $this->toArray(
            (string)$res->getBody()
        );
    }
}