<?php

namespace App\Quotation\Yahoo;

use App\Http\ClientInterface;
use App\Quotation\Exception\QuotationClientException;
use App\Quotation\QuotationsFetchRequest;
use Psr\Log\LoggerInterface;
use Throwable;

class YahooQuotationClient
{
    private const QUOTATION_LIST_URI = '%s/stock/v2/get-historical-data?frequency=1d&filter=history&period1=%d&period2=%d&symbol=%s';

    private ClientInterface $client;
    private LoggerInterface $logger;
    private string $host;
    private string $token;

    public function __construct(ClientInterface $client, LoggerInterface $logger, string $host, string $token)
    {
        $this->client = $client;
        $this->host = $host;
        $this->token = $token;
        $this->logger = $logger;
    }

    public function getQuotationsList(QuotationsFetchRequest $request): array
    {
        try {
            $response = $this->client->getJson(
                $this->generateGetQuotationListQuery($request),
                $this->getHeaders()
            );
        } catch (Throwable $e) {
            $this->logger->warning($e->getMessage());
            throw new QuotationClientException('Something went wrong');
        }

        if (isset($response['error'])) {
            $error = json_decode($response['error']['responseText'], true);
            throw new QuotationClientException($error['chart']['error']['description'] ?? '');
        }

        if (!isset($response['prices'])) {
            throw new QuotationClientException('Something went wrong');
        }

        return $response['prices'];
    }

    private function generateGetQuotationListQuery(QuotationsFetchRequest $request): string
    {
        return sprintf(
            self::QUOTATION_LIST_URI,
            $this->host,
            $request->getStartDate()->getTimestamp(),
            $request->getEndDate()->getTimestamp(),
            $request->getCompany()
        );
    }

    private function getHeaders(): array
    {
        return [
            'x-rapidapi-host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com',
            'x-rapidapi-key' => $this->token,
            'useQueryString' => 'true',
        ];
    }
}
