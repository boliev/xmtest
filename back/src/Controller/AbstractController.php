<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractController extends SymfonyController
{
    private const ERROR_FIELD = 'errors';

    public function jsonValidationError(ConstraintViolationListInterface $errorsList): JsonResponse
    {
        $responseErrors = [];
        foreach ($errorsList as $error) {
            $responseErrors[] = $error->getMessage();
        }
        $response = $this->json([self::ERROR_FIELD => $responseErrors]);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        return $response;
    }

    public function jsonBadRequest(string $message): JsonResponse
    {
        $response = $this->json([self::ERROR_FIELD => [$message]]);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        return $response;
    }

    public function notFound(string $message): JsonResponse
    {
        $response = $this->json([self::ERROR_FIELD => [$message]]);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);

        return $response;
    }
}
