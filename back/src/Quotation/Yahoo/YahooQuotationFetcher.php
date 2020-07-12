<?php

namespace App\Quotation\Yahoo;

use App\Quotation\QuotationDTO;
use App\Quotation\QuotationsFetcherInterface;
use App\Quotation\QuotationsFetchRequest;

class YahooQuotationFetcher implements QuotationsFetcherInterface
{
    private YahooQuotationClient $client;

    public function __construct(YahooQuotationClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return QuotationDTO[]
     */
    public function fetch(QuotationsFetchRequest $request): array
    {
        $rawQuotations = $this->client->getQuotationsList($request);

        return array_map(fn (array $raw) => new QuotationDTO(
            (new \DateTime())->setTimestamp((int) $raw['date']),
            $raw['open'], $raw['close'], $raw['high'], $raw['low'], $raw['volume']
        ), $rawQuotations);
    }
}
