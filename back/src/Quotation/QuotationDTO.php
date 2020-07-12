<?php

namespace App\Quotation;

use DateTimeInterface;

class QuotationDTO
{
    private DateTimeInterface $date;
    private float $open;
    private float $close;
    private float $high;
    private float $low;
    private int $volume;

    public function __construct(DateTimeInterface $date, float $open, float $close, float $high, float $low, int $volume)
    {
        $this->date = $date;
        $this->open = $open;
        $this->close = $close;
        $this->high = $high;
        $this->low = $low;
        $this->volume = $volume;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getOpen(): float
    {
        return $this->open;
    }

    public function getClose(): float
    {
        return $this->close;
    }

    public function getHigh(): float
    {
        return $this->high;
    }

    public function getVolume(): int
    {
        return $this->volume;
    }

    public function getLow(): float
    {
        return $this->low;
    }
}
