<?php

namespace App\Company\Datahub;

use App\Company\Cache\CompaniesCacheInterface;
use App\Company\Cache\Exception\CacheNotInitializedException;
use App\Company\CompaniesFetcherInterface;
use App\Company\CompanyDTO;
use App\Company\Exception\CompanyNotFoundException;

class DatahabCompaniesFetcher implements CompaniesFetcherInterface
{
    private DatahubCompaniesClient $client;
    private CompaniesCacheInterface $cache;

    public function __construct(DatahubCompaniesClient $client, CompaniesCacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getBySymbol(string $symbol): ?CompanyDTO
    {
        try {
            return $this->cache->getCompany($symbol);
        } catch (CacheNotInitializedException $e) {
            $this->cache->fillCache($this->client->fetchCompanies());

            return $this->cache->getCompany($symbol);
        }
    }

    public function getBySymbolOrThrow(string $symbol): CompanyDTO
    {
        $company = $this->getBySymbol($symbol);
        if (null === $company) {
            throw new CompanyNotFoundException('Company not found');
        }

        return $company;
    }

    /**
     * @return CompanyDTO[]
     */
    public function searchBySymbol(string $symbol): array
    {
        try {
            return $this->cache->searchCompanyBySymbol($symbol);
        } catch (CacheNotInitializedException $e) {
            $this->cache->fillCache($this->client->fetchCompanies());

            return $this->cache->searchCompanyBySymbol($symbol);
        }
    }
}
