<?php

namespace App\Company;

class CompanyDTO
{
    private string $name;
    private string $symbol;

    public function __construct(string $name, string $symbol)
    {
        $this->name = $name;
        $this->symbol = $symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
