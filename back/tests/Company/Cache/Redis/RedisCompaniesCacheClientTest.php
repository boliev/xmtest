<?php

namespace App\Tests\Company\Cache\Redis;

use App\Company\Cache\Exception\CacheNotInitializedException;
use App\Company\Cache\Redis\RedisCompaniesCache;
use App\Company\Cache\Redis\RedisCompaniesCacheClient;
use App\Company\CompanyDTO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Redis;

class RedisCompaniesCacheClientTest extends TestCase
{
	/** @var Redis|MockObject */
	private $redisMock;

	public function setUp()
	{
		$this->redisMock = $this->createMock(Redis::class);
		$this->redisMock->method('connect')->willReturn(true);
	}

	public function testGetHash_ReturnsNull(): void
	{
		$this->redisMock->method('exists')->willReturn(false);

		$client = new RedisCompaniesCacheClient($this->redisMock, '', '');
		$hash = $client->getHash('GOOG');

		$this->assertNull($hash);
	}

	/**
	 * @dataProvider companiesProvider
	 */
	public function testGetHash_ReturnsCompanies(array $companies): void
	{
		$this->redisMock->method('exists')->willReturn(true);
		$this->redisMock->method('hGet')->willReturn(serialize($companies));

		$client = new RedisCompaniesCacheClient($this->redisMock, '', '');
		$cachedCompanies = $client->getHash('ZZZ');

		$this->assertEquals($companies, $cachedCompanies);
	}

	public function testGetHash_ReturnsEmpty(): void
	{
		$this->redisMock->method('exists')->willReturn(true);
		$this->redisMock->method('hGet')->willReturn(false);

		$client = new RedisCompaniesCacheClient($this->redisMock, '', '');
		$cachedCompanies = $client->getHash('ZZZ');

		$this->assertEquals([], $cachedCompanies);
	}

	public function companiesProvider(): array
	{
		return [
			[
				[
					new CompanyDTO('Google Inc.', 'GOOG'),
					new CompanyDTO('Apple Inc.', 'AAPL'),
					new CompanyDTO('Amazon.com.', 'AMZN'),
				]
			]
		];
	}
}

