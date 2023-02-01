<?php

namespace App\Services;

use App\Repository\FicheRepository;

class FicheService
{
    private $ficheRepository;

    public function __construct(FicheRepository $ficheRepository)
    {
        $this->ficheRepository = $ficheRepository;
    }

    public function createDevisExample(){
        $datapdf = [
            [
                "lead"=> [
                    "nom" => "Talode",
                    "numero_identification_tva" => "0000001",
                    "numero_de_bon_de_commandes" => "0012023",
                    "status" => "aaa",
                    "raison_sociale" => "SAS",
                    "forme_juridique" => "dqsdqsdddd",
                    "adresse_entreprise" => "dqsdqsdddd",
                    "rcs_ou_numero_repoertoire_des_metiers" => "dqsdqsdddd",
                    "date_du_projet" => "dqsdqsdddd",
                    "siren" => "dqsdqsdddd",
                ],
                "finance" => [
                    "prix_horaire" => "",
                    "forfaitaire_main_doeuvre" => "",
                    "frais_de_deplacement" => "",
                    "conditions_paiment" => "",
                    "livraison_execution_du_contrat" => "",
                    "procedure_reclamation" => "",
                    "service_apres_vente" => "",
                    "total_ht" => "",
                    "total_tcc" => "",
                    "remise" => "",
                    "tva_applicables" => "",
                ],
                "customer" => [
                    "adresse_facturation" => "",
                    "nom_du_client" => "",
                    "adresse_client" => "",
                    "date_debut_contrat" => "",
                    "date_fin_contrat" => "",
                ],
                "details" => [
                    1 => [
                        "nom_prestation" => "",
                        "quantite" => "",
                        "prix_unitaire_ht" => "",
                    ],
                    2 => [
                        "nom_prestation" => "",
                        "quantite" => "",
                        "prix_unitaire_ht" => "",
                    ],
                ],
                "facture" => [
                    "date_facturation" => "",
                    "penalite_retard" => "",
                    "indemnite_forfaitaire" => "",
                ],
                "devis" => [
                    "duree_validite_devis" => ""
                ]
            ]
        ];

        dd($datapdf);
    }

}