<?php

namespace App\Quotation;

interface QuotationsFetcherInterface
{
    public function fetch(QuotationsFetchRequest $request): array;
}
