<?php

namespace App\Controller\Api;

use App\Entity\Fiche;
use App\Services\FicheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Helper\HttpResponseHelper;

#[Route('/fiches', name: 'fiches')]
class ApiFichesController extends AbstractController
{

    private FicheService $ficheService;

    function __construct(FicheService $ficheService)
    {
        $this->ficheService = $ficheService;
    }

    #[Route('/test', name: '_test')]
    function getDataUser(): JsonResponse
    {
        $response = $this->ficheService->createDevisExample();
        return $this->json(HttpResponseHelper::success($response));
    }
}
