<?php

namespace App\Controller\Api;

use App\Entity\Fiche;
use App\Services\FicheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Helper\HttpResponseHelper;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;

#[Route('/fiches', name: 'fiches')]
class ApiFichesController extends AbstractController
{

    private FicheService $ficheService;

    function __construct(FicheService $ficheService)
    {
        $this->ficheService = $ficheService;
    }

    #[Route('/test', name: '_test')]
    public function getDataUser(): Response
    {
        $datapdf = $this->ficheService->createDevisExample();
        // return $this->json(HttpResponseHelper::success($response));
        $dompdf = new Dompdf();
        $html = $this->renderView('devis/devis.html.twig', [
            'customer' => $datapdf["customer"],
            'lead' => $datapdf["lead"],
            'finance' => $datapdf["finance"],
            'details' => $datapdf["details"],
            'facture' => $datapdf["facture"],
            'devis' => $datapdf["devis"]
        ]); // contenu HTML
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdf = $dompdf->output();

        return new Response($pdf, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="file.pdf"'
        ));
    }
    //Création fiche
    //Lire la fiche
    //Supprimer fiche
    //Modifier une fiche (facultatif)
    //telecharger la fiche via pdf
}
