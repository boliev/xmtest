<?php

namespace App\Request;

use App\Quotation\QuotationsFetchRequest;
use Symfony\Component\Validator\Constraints as Assert;

class QuotationListRequest
{
    /**
     * @Assert\NotBlank(message="Company symbol cannot be empty")
     * @Assert\Type(type="alpha", message="Alphabetic symbols Only")
     */
    private string $company;

    /**
     * @Assert\NotBlank(message="Email address cannot be empty")
     * @Assert\Email(message="Invalid email address")
     */
    private string $email;

    /**
     * @Assert\NotBlank(message="Start date cannot be empty")
     * @Assert\Date(message="Invalid start date")
     */
    private string $startDate;

    /**
     * @Assert\NotBlank(message="End date cannot be empty")
     * @Assert\Date(message="Invalid end date")
     */
    private string $endDate;

    private ?QuotationsFetchRequest $quotationsFetchRequest;

    public function __construct(string $company, string $email, string $startDate, string $endDate)
    {
        $this->company = $company;
        $this->email = $email;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->quotationsFetchRequest = null;
    }

    public function getCompany(): string
    {
        return strtoupper($this->company);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getQuotationFetchRequest()
    {
        if (is_null($this->quotationsFetchRequest)) {
            $startDate = new \DateTime($this->getStartDate());
            $endDate = new \DateTime($this->getEndDate());
            $this->quotationsFetchRequest = new QuotationsFetchRequest(
                $this->getCompany(),
                $startDate,
                $endDate
            );
        }

        return $this->quotationsFetchRequest;
    }
}
