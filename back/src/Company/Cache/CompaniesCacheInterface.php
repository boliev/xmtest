<?php

namespace App\Company\Cache;

use App\Company\Cache\Exception\CacheNotInitializedException;
use App\Company\CompanyDTO;

interface CompaniesCacheInterface
{
    /**
     * @throws CacheNotInitializedException
     */
    public function getCompany(string $symbol): ?CompanyDTO;

    /**
     * @return CompanyDTO[]
     *
     * @throws CacheNotInitializedException
     */
    public function searchCompanyBySymbol(string $symbol): array;

    /**
     * @param CompanyDTO[] $companies
     */
    public function fillCache(array $companies): void;
}
