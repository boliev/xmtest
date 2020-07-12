<?php

namespace App\Tests\Quotation;

use App\Quotation\Exception\BadDateHttpException;
use App\Quotation\QuotationsFetchRequest;
use DateInterval;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class QuotationFetchRequestTest extends TestCase
{
    /**
     * @dataProvider datesProvider
     */
    public function testCheckDates_ThrowsException(DateTimeInterface $start, DateTimeInterface $end): void
    {
        $this->expectException(BadDateHttpException::class);
        new QuotationsFetchRequest('GOOG', $start, $end);
    }

    public function datesProvider(): array
    {
        return [
            [new DateTime('2020-07-09'), new DateTime('2020-07-08')],
            [(new DateTime())->add(new DateInterval('P1D')), (new DateTime())->add(new DateInterval('P2D'))],
            [(new DateTime())->sub(new DateInterval('P1D')), (new DateTime())->add(new DateInterval('P2D'))],
        ];
    }
}
