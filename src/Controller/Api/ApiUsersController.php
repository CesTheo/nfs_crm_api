<?php

namespace App\Controller\Api;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\User;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\HttpResponseHelper;

#[Route('/users', name: 'users')]
class ApiUsersController extends AbstractController
{
    private UserService $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/me', name: '_me', methods: ['GET'])]
    function getDataUser(#[CurrentUser] ?User $user): JsonResponse
    {
        if(!$user){
            return HttpResponseHelper::notFound("Utilisateur non trouvé");
        }

        return $this->json(HttpResponseHelper::success($user->toArray()));
    }

    #[Route('/', name: '_index', methods: ['GET'])]
    function getAllUsers(Request $request): JsonResponse
    {
        $page = intval($request->query->get('page')) ?? 1;
        $limit = intval($request->query->get('limit')) ?? 10;

        return $this->json(HttpResponseHelper::success(
            $this->userService->search([
                'paginate' => [
                    'page' => $page,
                    'limit' => $limit
                ]
            ])
        ));
    }

    #[Route('/api/createUser', name: 'createUser', methods: ["POST"])]
    function createUser(Request $request, UserService $userService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['roles']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name']) || !isset($data['phone']) || !isset($data['verify']) || !isset($data['society'])) {
            throw new BadRequestHttpException("Il manque un ou plusieurs champs dans la requete");
        }

        $userService->createUser($data['email'], $data['roles'], $data['password'], $data['first_name'], $data['last_name'], $data['phone'], $data['verify'], $data['society']);

        return $this->json([
            "code" => 200,
            "message" => "Utilsateur crée"
        ]);
    
    }

    #[Route('/api/updateUser/{id}', name: 'updateUser', methods: ["PUT"])]
    public function updateUser(int $id, Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

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
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
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

    #[Route("/api/getUser/{id}", name: "readUser")]
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
