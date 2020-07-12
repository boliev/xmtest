<?php

namespace App\Controller;

use App\Company\CompaniesFetcherInterface;
use App\Quotation\Exception\BadDateHttpException;
use App\Quotation\Exception\QuotationClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1", name="v1_")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/company/search/{symbol}", name="search_company", methods={"GET"}, requirements={"symbol"="[a-zA-Z]+"})
     */
    public function searchCompany(string $symbol, CompaniesFetcherInterface $companyFetcher): Response
    {
        try {
            $companies = $companyFetcher->searchBySymbol(strtoupper($symbol));
        } catch (BadDateHttpException | QuotationClientException $e) {
            return $this->jsonBadRequest($e->getMessage());
        }

        return $this->json($companies);
    }

    /**
     * @Route("/company/{symbol}", name="get_company", methods={"GET"}, requirements={"symbol"="[a-zA-Z]+"})
     */
    public function getCompany(string $symbol, CompaniesFetcherInterface $companyFetcher): Response
    {
        try {
            $company = $companyFetcher->getBySymbol(strtoupper($symbol));
        } catch (BadDateHttpException | QuotationClientException $e) {
            return $this->jsonBadRequest($e->getMessage());
        }

        if (is_null($company)) {
            return $this->notFound('Company not found');
        }

        return $this->json($company);
    }
}
