<?php

namespace App\Services;

use App\Entity\Fiche;
use App\Repository\FicheRepository;
use App\Repository\UserRepository;

class FicheService
{
    private $ficheRepository;
    private $userRepository;

    public function __construct(FicheRepository $ficheRepository, UserRepository $userRepository)
    {
        $this->ficheRepository = $ficheRepository;
        $this->userRepository = $userRepository;
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

        $this->ficheRepository->save($fiche, true);

        return "Fiche crée";
    }


    public function searchFichebyId($id)
    {
        return $this->ficheRepository->findOneBy(['id' => $id]);
    }

}