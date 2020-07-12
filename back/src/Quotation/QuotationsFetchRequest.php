<?php

namespace App\Quotation;

use App\Quotation\Exception\BadDateHttpException;
use DateTime;
use DateTimeInterface;

class QuotationsFetchRequest
{
    private string $company;
    private DateTimeInterface $startDate;
    private DateTimeInterface $endDate;

    public function __construct(string $company, DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        $this->checkDates($startDate, $endDate);
        $this->company = $company;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->endDate;
    }

    private function checkDates(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        if ($startDate > $endDate) {
            throw new BadDateHttpException('End Date must be greater or equal than Start Date');
        }
        $today = new DateTime('today');
        if ($startDate > $today) {
            throw new BadDateHttpException('Start Date in the future');
        }
        if ($endDate > $today) {
            throw new BadDateHttpException('End Date in the future');
        }
    }
}
