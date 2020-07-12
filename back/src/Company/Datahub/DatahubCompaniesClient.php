<?php

namespace App\Company\Datahub;

use App\Company\CompanyDTO;
use App\Http\ClientInterface;
use App\Quotation\Exception\QuotationClientException;
use Psr\Log\LoggerInterface;
use Throwable;

class DatahubCompaniesClient
{
    private const COMPANIES_LIST_URI = '%s/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';

    private ClientInterface $client;
    private string $host;
    private LoggerInterface $logger;

    public function __construct(ClientInterface $client, string $host, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->host = $host;
        $this->logger = $logger;
    }

    /**
     * @return CompanyDTO[]
     */
    public function fetchCompanies(): array
    {
        try {
            $companies = $this->client->getJson($this->generateGetQuotationListQuery(), []);
        } catch (Throwable $e) {
            $this->logger->warning($e->getMessage());
            throw new QuotationClientException('Something went wrong');
        }

        return array_map(fn (array $raw) => new CompanyDTO($raw['Company Name'], $raw['Symbol']), $companies);
    }

    private function generateGetQuotationListQuery(): string
    {
        return sprintf(
            self::COMPANIES_LIST_URI,
            $this->host,
        );
    }
}
