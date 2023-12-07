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

    #[Route(path: '/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function price(Request $request, ValidatorInterface $validator): JsonResponse
    {
        try {
            $form = new PriceCalculatorForm();
            $form->load($request);
            $errors = $validator->validate($form);

            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 400);
            }

            return $this->json($this->calculatorService->calculatePrice($form));
        } catch (\Throwable $e) {
            // TODO: add handler and logging
            return $this->json(['errors' => [[$e->getMessage()]]], 400);
        }
    }
}
