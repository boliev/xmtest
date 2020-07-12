<?php

namespace App\Http;

use GuzzleHttp\Client as GuzzleClient;

class Client implements ClientInterface
{
    private GuzzleClient $client;

    private array $headers = [
    ];

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function getJson(string $url, array $headers = []): array
    {
        return json_decode($this->get($url, $headers), true);
    }

    private function get(string $url, array $headers = []): string
    {
        foreach ($headers as $header => $value) {
            $this->headers[$header] = $value;
        }

        $res = $this->client->get($url, ['headers' => $this->headers]);

        return $res->getBody()->getContents();
    }
}
