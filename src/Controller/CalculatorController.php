<?php

namespace App\Controller;

use App\Form\PriceCalculatorForm;
use App\Service\CalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class CalculatorController extends AbstractController
{
    private CalculatorService $calculatorService;

    public function __construct(CalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    #[Route(path: '/calculate-price', name: 'calculate_price', methods: ['GET', 'POST'])]
    public function price(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $form = new PriceCalculatorForm();
        $form->load($request);
        $errors = $validator->validate($form);

        $responseResult = [
            'success' => false,
            '_links' => [
                'self' => $request->getUri()
            ]
        ];

        if (count($errors) > 0) {
            $responseResult['errors'] = $errors;
            return $this->json($responseResult, 400);
        }

        try {
            $price = $this->calculatorService->calculatePrice($form);
            $responseResult['success'] = true;
            $responseResult['data'] = [
                'price' => $price
            ];

            return $this->json($responseResult);
        } catch (\Throwable $e) {
            // TODO: add handler and logging
            $responseResult['errors'] = [[$e->getMessage()]];
            return $this->json($responseResult, 400);
        }
    }
}
