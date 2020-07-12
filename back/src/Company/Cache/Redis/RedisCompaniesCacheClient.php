<?php

namespace App\Company\Cache\Redis;

use App\Company\CompanyDTO;
use Redis;

class RedisCompaniesCacheClient
{
    private const COMPANIES_KEY = 'xm:companies';
    private const EXPIRE_SECS = 3600 * 24;
    private Redis $client;

    public function __construct(Redis $client, string $host, string $port)
    {
        $this->client = $client;
        $this->client->connect($host, $port);
    }

    /**
     * @return CompanyDTO[]|null
     */
    public function getHash(string $symbol): ?array
    {
        if (!$this->client->exists(self::COMPANIES_KEY)) {
            return null;
        }

        $companies = $this->client->hGet(self::COMPANIES_KEY, $this->symbolToHash($symbol));
        if (false === $companies) {
            return [];
        }

        return unserialize($companies);
    }

    public function setHash($key, array $companies): void
    {
        $this->client->hSet(self::COMPANIES_KEY, $key, serialize($companies));
        $this->client->expire(self::COMPANIES_KEY, self::EXPIRE_SECS);
    }

    public function symbolToHash(string $symbol)
    {
        return substr($symbol, 0, 2);
    }
}
