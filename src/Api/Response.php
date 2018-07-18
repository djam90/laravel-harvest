<?php

namespace Djam90\Harvest\Api;

use Psr\Http\Message\ResponseInterface;

class Response
{
    private $response;
    private $json;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        $this->json = (string) $this->response->getBody();
    }

    public function toArray()
    {
        return json_decode($this->json, true);
    }

    public function toJson()
    {
        return $this->json;
    }

    public function toObject()
    {
        return json_decode(
            json_encode($this->toArray())
        );
    }
}