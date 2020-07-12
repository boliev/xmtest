<?php

namespace App\Http;

interface ClientInterface
{
    public function getJson(string $url, array $headers = []): array;
}
