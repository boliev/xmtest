<?php

namespace App\Company;

interface CompaniesFetcherInterface
{
    public function getBySymbol(string $symbol): ?CompanyDTO;

    public function getBySymbolOrThrow(string $symbol): CompanyDTO;

    /**
     * @return CompanyDTO[]
     */
    public function searchBySymbol(string $symbol): array;
}
