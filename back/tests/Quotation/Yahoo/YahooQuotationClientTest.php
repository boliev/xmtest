<?php

namespace App\Tests\Quotation\Yahoo;

use App\Http\ClientInterface;
use App\Quotation\Exception\QuotationClientException;
use App\Quotation\QuotationsFetchRequest;
use App\Quotation\Yahoo\YahooQuotationClient;
use DateTime;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class YahooQuotationClientTest extends TestCase
{
	/** @var MockObject|LoggerInterface */
	private $logger;
	/** @var ClientInterface|MockObject */
	private $clientMock;
	private QuotationsFetchRequest $testRequest;

	public function setUp()
	{
		$this->clientMock = $this->createMock(ClientInterface::class);
		$this->logger = $this->createMock(LoggerInterface::class);
		$this->testRequest = new QuotationsFetchRequest('', new DateTime('2020-07-01'), new DateTime('2020-07-02'));
	}
	public function testGetQuotationsList_ThrowsClientException(): void
	{

		$this->clientMock->method('getJson')->willThrowException(new Exception(''));

		$this->expectException(QuotationClientException::class);

		$client = new YahooQuotationClient($this->clientMock, $this->logger, '', '');
		$client->getQuotationsList($this->testRequest);
	}

	public function testGetQuotationsList_ErrorInResponse_ThrowsClientException(): void
	{
		$this->clientMock->method('getJson')->willReturn(['error' => ['responseText' => json_encode([])]]);

		$this->expectException(QuotationClientException::class);

		$client = new YahooQuotationClient($this->clientMock, $this->logger, '', '');
		$client->getQuotationsList($this->testRequest);
	}

	public function testGetQuotationsList_HasNoPriced_ThrowsClientException(): void
	{
		$this->clientMock->method('getJson')->willReturn([]);

		$this->expectException(QuotationClientException::class);

		$client = new YahooQuotationClient($this->clientMock, $this->logger, '', '');
		$client->getQuotationsList($this->testRequest);
	}

	public function testGetQuotationsList_Success(): void
	{
		$this->clientMock->method('getJson')->willReturn(['prices' => []]);

		$client = new YahooQuotationClient($this->clientMock, $this->logger, '', '');
		$prices = $client->getQuotationsList($this->testRequest);
		$this->assertEquals([], $prices);
	}

}
