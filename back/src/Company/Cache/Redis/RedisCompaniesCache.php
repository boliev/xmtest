<?php

namespace App\Company\Cache\Redis;

use App\Company\Cache\CompaniesCacheInterface;
use App\Company\Cache\Exception\CacheNotInitializedException;
use App\Company\CompanyDTO;

class RedisCompaniesCache implements CompaniesCacheInterface
{
    private RedisCompaniesCacheClient $client;

    public function __construct(RedisCompaniesCacheClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws CacheNotInitializedException
     */
    public function getCompany(string $symbol): ?CompanyDTO
    {
        foreach ($this->getHashBySymbol($symbol) as $company) {
            if ($company->getSymbol() === $symbol) {
                return $company;
            }
        }

        return null;
    }

    public function searchCompanyBySymbol(string $symbol): array
    {
        $result = [];
        foreach ($this->getHashBySymbol($symbol) as $company) {
            if (0 === strpos($company->getSymbol(), $symbol)) {
                $result[] = $company;
            }
        }

        return $result;
    }

    /**
     * @return CompanyDTO[]
     *
     * @throws CacheNotInitializedException
     */
    private function getHashBySymbol(string $symbol): array
    {
        $companies = $this->client->getHash($symbol);
        if (null === $companies) {
            throw new CacheNotInitializedException();
        }

        return $companies;
    }

    /**
     * @param CompanyDTO[] $companies
     */
    public function fillCache(array $companies): void
    {
        $cache = [];
        foreach ($companies as $company) {
            $cache[$this->client->symbolToHash($company->getSymbol())][] = $company;
        }

        foreach ($cache as $key => $companies) {
            $this->client->setHash($key, $companies);
        }
    }
}
