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

    #[Route('/deleteDevis/{id}', name: '_deletedevis')]
    public function deleteDevis(int $id)
    {
        $IsDestroy = $this->ficheService->deleteFiche($id);
        if($IsDestroy){
            return new JsonResponse([
                "status" => 200,
                "message" => "L'élement à était détruie"
            ]);
        }else{
            return new JsonResponse([
                "status" => 200,
                "message" => "L'élement inconnu"
            ]);
        }
    }

    #[Route('/getUserDevis/{user_id}', name: '_getuserdevis')]
    public function getuserDevis(int $user_id)
    {
        $fiches = $this->ficheService->getFicheByUser($user_id);
        if($fiches){
            return new JsonResponse([
                "status" => 200,
                "message" => $fiches
            ]);
        }else{
            return new JsonResponse([
                "status" => 200,
                "message" => "Il n'y a pas de fiches devis"
            ]);
        }
    }

    #[Route('/getCreateurDevis/{createur_id}', name: '_getcreateuruserdevis')]
    public function getFicheByUserCreateur(int $createur_id)
    {
        $fiches = $this->ficheService->getFicheByUserCreateur($createur_id);
        if($fiches){
            return new JsonResponse([
                "status" => 200,
                "message" => $fiches
            ]);
        }else{
            return new JsonResponse([
                "status" => 200,
                "message" => "Il n'y a pas de fiches devis"
            ]);
        }
    }

}
