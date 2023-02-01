<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FicheController extends AbstractController
{
    #[Route('/fiche', name: 'app_fiche')]
    public function index(): Response
    {
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
        ]);
    }
    //  Les routes à faire
    //  []GetFiche{id}
    //  []DeleteFiche{id}
    //  []CreateFiche{id}
    //  []GetFichePerUser{id}
    //  []GetFicheOwn
    //  []UpdateFiche
}
