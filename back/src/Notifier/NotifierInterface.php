<?php

namespace App\Notifier;

use App\Company\CompanyDTO;
use App\Quotation\QuotationsFetchRequest;

interface NotifierInterface
{
    public function notifyAboutSuccessRequest(string $address, CompanyDTO $company, QuotationsFetchRequest $request): void;
}
