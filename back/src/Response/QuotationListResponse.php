<?php

namespace App\Response;

use App\Company\CompanyDTO;
use App\Quotation\QuotationDTO;

class QuotationListResponse
{
    private CompanyDTO $company;
    /** @var QuotationDTO[] */
    private array $quotations;

    public function __construct(CompanyDTO $company, array $quotations)
    {
        $this->company = $company;
        $this->quotations = $quotations;
    }

    public function getCompany(): CompanyDTO
    {
        return $this->company;
    }

    /**
     * @return QuotationDTO[]
     */
    public function getQuotations(): array
    {
        return $this->quotations;
    }
}
