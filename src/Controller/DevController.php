<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

//FAKE Route juste pour faire des testes

class DevController extends AbstractController
{
    #[Route('/api/dev', name: 'app_dev')]
    public function index(PersistenceManagerRegistry $doctrine): JsonResponse
    {
        return $this->json([
            "utilisateur" => "users"
        ]);
    }
}
