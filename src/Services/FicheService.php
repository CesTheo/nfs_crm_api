<?php

namespace App\Services;

use App\Entity\Fiche;
use App\Repository\FicheRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\MakerBundle\Doctrine\RelationOneToOne;

class FicheService
{
    private $ficheRepository;
    private $userRepository;
    private $entityManager;
    private $user;

    public function __construct(FicheRepository $ficheRepository, UserRepository $userRepository, ManagerRegistry $doctrine, #[CurrentUser] ?User $user)
    {
        $this->ficheRepository = $ficheRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $doctrine->getManager();
        $this->user = $user;

    }

    public function createDevisExample(){
        $datapdf = [
            "lead"=> [
                "nom" => "Talodev",
                "numero_de_bon_de_commandes" => "0112023",
                "forme_juridique" => "SAS",
                "adresse_entreprise_line1" => "36 rue Victor Hugo",
                "adresse_entreprise_line2" => "76000 Rouen",
                "date_du_projet" => "12/01/2023",
                "siret" => "32353464565",
            ],
            "finance" => [
                "total_ht" => "1000",
                "total_ttc" => "1200",
                "tva_applicables" => "20",
            ],
            "customer" => [
                "adresse_facturation_1" => "2 rue victor hugo",
                "adresse_facturation_2" => "76000 Rouen",
                "entreprise" => "Tesla",
                "adresse_client_1" => "2 rue victor hugo",
                "adresse_client_2" => "76000 Rouen",
                "email" => "elonmusk@tesla.com",
                "phone" => "0600000000",
            ],
            "details" => [
                1 => [
                    "nom_prestation" => "Web Symfony",
                    "quantite" => "5",
                    "prix_unitaire_ht" => "35",
                ],
                2 => [
                    "nom_prestation" => "Création portifolio",
                    "quantite" => "1",
                    "prix_unitaire_ht" => "500",
                ],
                3 => [
                    "nom_prestation" => "Création portifolio",
                    "quantite" => "1",
                    "prix_unitaire_ht" => "500",
                ],
                4 => [
                    "nom_prestation" => "Création portifolio",
                    "quantite" => "1",
                    "prix_unitaire_ht" => "500",
                ],
                5 => [
                    "nom_prestation" => "Création portifolio",
                    "quantite" => "1",
                    "prix_unitaire_ht" => "500",
                ],
                6 => [
                    "nom_prestation" => "Création portifolio",
                    "quantite" => "1",
                    "prix_unitaire_ht" => "500",
                ],

            ],
            "facture" => [
                "date_facturation" => "",
                "penalite_retard" => "",
                "indemnite_forfaitaire" => "",
                "numero" => "2023001",
            ],
            "devis" => [
                "duree_validite_devis" => "27/04/2001"
            ]
        ];
        return $datapdf;
    }

    public function createDevis($data){

        $user = $this->userRepository->findOneBy(['email' => $data["email_target"]]);
        if ($user === null) {
            return "Utilisateur inconnu";
        }

        $fiche = new Fiche();
        $fiche->setUser($user);
        $fiche->setName($data["name"]);
        $fiche->setData($data["data"]);
        $fiche->setCategorie($data["categorie"]);
        $fiche->setIdCreateur($user->getId());

        $this->ficheRepository->save($fiche, true);

        return true;
    }


    public function searchFichebyId($id)
    {
        return $this->ficheRepository->findOneBy(['id' => $id]);
    }

    public function deleteFiche($id)
    {
        $fiche = $this->ficheRepository->find($id);
        $this->ficheRepository->remove($fiche);
        $this->entityManager->flush();
        return true;
    }

    public function getFicheByUser($id)
    {
        $fiches = $this->ficheRepository->findBy(
            ['User' => $id]
        );
        foreach($fiches as $fiche){
            $data[] = [
                'id' => $fiche->getId(),
                'name' => $fiche->getName(),
                'categorie' => $fiche->getCategorie(),
                'data' => $fiche->getData(),
                'user_email' => $fiche->getUser()->getEmail(),
                'user_id' => $fiche->getUser()->getId(),
                'user_createur_id' => $fiche->getIdCreateur(),
            ];
        }
        return $data;
    }

    public function getFicheByUserCreateur($id)
    {
        $fiches = $this->ficheRepository->findBy(
            ['id_createur' => $id]
        );
        foreach($fiches as $fiche){
            $data[] = [
                'id' => $fiche->getId(),
                'name' => $fiche->getName(),
                'categorie' => $fiche->getCategorie(),
                'data' => $fiche->getData(),
                'user_email' => $fiche->getUser()->getEmail(),
                'user_id' => $fiche->getUser()->getId(),
                'user_createur_id' => $fiche->getIdCreateur(),
            ];
        }
        return $data;
    }
}