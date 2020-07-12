<?php

namespace App\Tests\Company\Cache\Redis;

use App\Company\Cache\Exception\CacheNotInitializedException;
use App\Company\Cache\Redis\RedisCompaniesCache;
use App\Company\Cache\Redis\RedisCompaniesCacheClient;
use App\Company\CompanyDTO;
use PHPUnit\Framework\TestCase;

class RedisCompaniesCacheTest extends TestCase
{
	public function testGetCompany_CacheNotInitialized_ThrowsException(): void
	{
		$cacheClient = $this->createMock(RedisCompaniesCacheClient::class);
		$cacheClient->method('getHash')->willReturn(null);
		$cache = new RedisCompaniesCache($cacheClient);

		$this->expectException(CacheNotInitializedException::class);
		$cache->getCompany('');
	}

	/**
	 * @dataProvider companiesProvider
	 */
	public function testGetCompany_ReturnsCompany(array $companies): void
	{
		$cacheClient = $this->createMock(RedisCompaniesCacheClient::class);
		$cacheClient->method('getHash')->willReturn($companies);
		$cache = new RedisCompaniesCache($cacheClient);

		$company = $cache->getCompany('AAPL');

		$this->assertEquals('Apple Inc.', $company->getName());
	}

	/**
	 * @dataProvider companiesProvider
	 */
	public function testGetCompany_ReturnsNull(array $companies): void
	{
		$cacheClient = $this->createMock(RedisCompaniesCacheClient::class);
		$cacheClient->method('getHash')->willReturn($companies);
		$cache = new RedisCompaniesCache($cacheClient);

		$company = $cache->getCompany('ZZZZ');

		$this->assertNull($company);
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

