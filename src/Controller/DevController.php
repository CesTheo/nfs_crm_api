<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

//FAKE Route juste pour faire des testes
class DevController extends AbstractController
{
    #[Route('/api/dev', name: 'app_dev')]
    public function readUser(ManagerRegistry $doctrine): JsonResponse
    {

        $repository2 = $doctrine->getRepository(Fiche::class);
        $fiches = $repository2->findBy(["User" => 26]);

        $fichesArray = array();
        foreach ($fiches as $fiche) {
            $fichesArray[] = $fiche->toArray();
        }

        return $this->json([
            "code" => 200,
            "message" => $fichesArray
        ]);

    }
    //Route pour afficher les infos client du dashboard en fonction du compte connecter
    #[Route('/api/dev1', name: 'app_dev1')]
    public function readUserFiche(ManagerRegistry $doctrine, #[CurrentUser] ?User $user): JsonResponse
    {

        $repository2 = $doctrine->getRepository(Fiche::class);
        $fiches = $repository2->findBy(["User" => $user->getId()]);
        $fichesArray = array();
        foreach ($fiches as $fiche) {
            $fichesArray[] = $fiche->toArray();
        }

        return $this->json([
            "code" => 200,
            "fiches" => $fichesArray
        ]);

    }
}


