<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/api/getDataUser', name: 'getDataUser')]
    public function getDataUser(#[CurrentUser] ?User $user): JsonResponse
    {
        if(!$user){
            return $this->json([
                "code" => 404,
                "message" => "Utilisateur non trouvé",
            ]);
        }

        return $this->json([
            "code" => 200,
            "message" => $user
        ]);
    }


    #[Route('/api/getAllUser', name: 'getAllUser')]
    public function getAllUser(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();

        // Convertir chaque objet User en un tableau car référence circulaire 
        $usersArray = array();
        foreach ($users as $user) {
            $usersArray[] = $user->toArray();
        }

        return $this->json([
                "code" => 200,
                "message" => $usersArray
            ]);
    }

    #[Route('/api/createUser', name: 'createUser', methods: ["POST"])]
    public function createUser(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Vérifier si les données de la requête sont bien présentes et ont le bon format
        if (!isset($data['email']) || !isset($data['roles']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name']) || !isset($data['phone']) || !isset($data['verify']) || !isset($data['society'])) {
            return $this->json([
                "code" => 400,
                "message" => "Données de la requête manquantes ou incorrectes"
            ]);
        }
    
        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setPassword($data['password']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setPhone($data['phone']);
        $user->setVerify($data['verify']);
        $user->setSociety($data['society']);
    
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    
        return $this->json([
            "code" => 201,
            "message" => "Utilisateur créé avec succès"
        ]);
    }

    #[Route('/api/updateUser/{id}', name: 'updateUser', methods: ["PUT"])]
    public function updateUser(int $id, Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier si les données de la requête sont bien présentes et ont le bon format
        if (!isset($data['email']) || !isset($data['roles']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name']) || !isset($data['phone']) || !isset($data['verify']) || !isset($data['society'])) {
            return $this->json([
                "code" => 400,
                "message" => "Données de la requête manquantes ou incorrectes"
            ]);
        }

        $repository = $doctrine->getRepository(User::class);
        $user = $repository->findOneById($id);

        if(!$user){
            return $this->json([
                "code" => 404,
                "message" => "Utilisateur non trouvé",
            ]);
        }

        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setPassword($data['password']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setPhone($data['phone']);
        $user->setVerify($data['verify']);
        $user->setSociety($data['society']);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            "code" => 200,
            "message" => "Utilisateur mis à jour avec succès"
        ]);
    }

    #[Route("/api/readUser/{id}", name: "readUser")]
    public function readUser(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(User::class);
        $user = $repository->find($id);
        
        if (!$user) {
            return $this->json([
                "code" => 404,
                "message" => "Utilisateur non trouvé"
            ]);
        }

        return $this->json([
            "code" => 200,
            "message" => $user->toArray()
        ]);
    }
}
