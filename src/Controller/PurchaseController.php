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

            $responseResult = [
                'success' => false,
                '_links' => [
                    'self' => $request->getUri()
                ]
            ];

            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 400);
            }

            $this->purchaseService->buy($form);
            $responseResult['success'] = true;

            return $this->json($responseResult);
        } catch (\Throwable $e) {
            // TODO: add handler and logging
            $responseResult['errors'] = [[$e->getMessage()]];
            return $this->json($responseResult, 400);
        }
    }
}
