<?php

namespace App\Controller;

use App\Company\CompaniesFetcherInterface;
use App\Company\Exception\CompanyNotFoundException;
use App\Notifier\NotifierInterface;
use App\Quotation\Exception\BadDateHttpException;
use App\Quotation\Exception\QuotationClientException;
use App\Quotation\QuotationsFetcherInterface;
use App\Request\QuotationListRequest;
use App\Response\QuotationListResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/v1", name="v1_")
 */
class QuotationController extends AbstractController
{
    /**
     * @Route("/quotations", name="quotations_list", methods={"GET"})
     */
    public function quotations(
        Request $request,
        ValidatorInterface $validator,
        QuotationsFetcherInterface $quotationFetcher,
        CompaniesFetcherInterface $companyFetcher,
        NotifierInterface $notifier
    ): Response {
        $quotationRequest = new QuotationListRequest(
            $request->get('company', ''),
            $request->get('email', ''),
            $request->get('startDate', ''),
            $request->get('endDate', '')
        );

        $errors = $validator->validate($quotationRequest);
        if ($errors->count() > 0) {
            return $this->jsonValidationError($errors);
        }

		try {
			$quotationFetchRequest = $quotationRequest->getQuotationFetchRequest();
			$company = $companyFetcher->getBySymbolOrThrow($quotationRequest->getCompany());
            $quotations = $quotationFetcher->fetch($quotationFetchRequest);
        } catch (BadDateHttpException | QuotationClientException | CompanyNotFoundException $e) {
            return $this->jsonBadRequest($e->getMessage());
        }

        $notifier->notifyAboutSuccessRequest($quotationRequest->getEmail(), $company, $quotationFetchRequest);

        return $this->json(new QuotationListResponse($company, $quotations));
    }
}
