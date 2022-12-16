<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    #[Route('/api/getDataUser', name: 'getDataUser')]
    public function getDataUser(#[CurrentUser] ?User $user): Response
    {
        if(!$user){
            return $this->json([
                "code" => 404,
                "message" => "Utilisateur non trouvé",
            ]);
        }

        return $this->json([
            $user
        ]);
    }

    #[Route('/api/getAllUser', name: 'getAllUser')]
    public function getAllUser(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        return $this->json([
            $users
        ]);
    }
}
