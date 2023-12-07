<?php

namespace App\Controller;

use App\Form\PurchaseForm;
use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class PurchaseController extends AbstractController
{
    private PurchaseService $purchaseService;


    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    #[Route(path: '/purchase', name: 'purchase', methods: ['POST'])]
    public function index(Request $request, ValidatorInterface $validator): JsonResponse
    {
        try {
            $form = new PurchaseForm();
            $form->load($request);
            $errors = $validator->validate($form);

            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 400);
            }

            return $this->json($this->purchaseService->buy($form));
        } catch (\Throwable $e) {
            // TODO: add handler and logging
            return $this->json(['errors' => [[$e->getMessage()]]], 400);
        }
    }
}
