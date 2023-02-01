<?php

namespace App\Controller\Api;

use App\Services\FicheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/fiches', name: 'fiches')]
class ApiFichesController extends AbstractController
{

    private FicheService $ficheService;

    function __construct(FicheService $ficheService)
    {
        $this->ficheService = $ficheService;
    }

    #[Route('/testDevis', name: '_test')]
    public function getDataUser(): Response
    {
        $datapdf = $this->ficheService->createDevisExample();
        $dompdf = new Dompdf();
        $html = $this->renderView('devis/devis.html.twig', [
            'customer' => $datapdf["customer"],
            'lead' => $datapdf["lead"],
            'finance' => $datapdf["finance"],
            'details' => $datapdf["details"],
            'facture' => $datapdf["facture"],
            'devis' => $datapdf["devis"]
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdf = $dompdf->output();

        return new Response($pdf, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="file.pdf"'
        ));
    }

    //Create Devis
    #[Route('/createDevis', name: '_createdevis')]
    public function createDevis(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return new JsonResponse($this->ficheService->createDevis($data));

    }

    //Read Devis
    #[Route('/readDevis/{id}', name: '_readdevis')]
    public function readDevis(int $id)
    {
        $fiche = $this->ficheService->searchFichebyId($id);
        $datapdf = $fiche->getData();
        $dompdf = new Dompdf();
        $html = $this->renderView('devis/devis.html.twig', [
            'customer' => $datapdf["customer"],
            'lead' => $datapdf["lead"],
            'finance' => $datapdf["finance"],
            'details' => $datapdf["details"],
            'facture' => $datapdf["facture"],
            'devis' => $datapdf["devis"]
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdf = $dompdf->output();

        return new Response($pdf, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="file.pdf"'
        ));
    }
}
