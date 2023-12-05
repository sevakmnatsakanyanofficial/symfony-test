<?php

namespace App\Controller;

use App\Service\CalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CalculatorController extends AbstractController
{
    private CalculatorService $calculatorService;

    public function __construct(CalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    #[Route('/calculate-price', name: 'calculate_price')]
    public function price(): Response
    {
        $products = $this->calculatorService->calculatePrice();

        return $this->json($products);
    }
}
