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
                "information"=> [
                    "tesaat" => "dddd",
                    "tessssst" => "dddsdqsdqd",
                    "tessst" => "dddsdqdd",
                    "test" => "dqsdqsdddd",
                ]
            ]
        ];

        dd($datapdf);
    }

}