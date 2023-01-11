<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Helper\HttpResponseHelper;

class UserController extends AbstractController
{
    #[Route('/api/getDataUser', name: 'getDataUser')]
    public function getDataUser(#[CurrentUser] ?User $user, UserService $userService): JsonResponse
    {
        $response = $userService->getUser($user->getId());

        return $this->json(HttpResponseHelper::success($response));
    }

    #[Route('/api/getAllUser', name: 'getAllUser')]
    public function getAllUser(UserService $userService): JsonResponse
    {
        $response = $userService->getAllUser();

        return $this->json(HttpResponseHelper::success($response));
    }

    #[Route('/api/createUser', name: 'createUser', methods: ["POST"])]
    public function createUser(Request $request, UserService $userService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['roles']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name']) || !isset($data['phone']) || !isset($data['verify']) || !isset($data['society'])) {
            throw new BadRequestHttpException("Il manque un ou plusieurs champs dans la requete");
        }

        $userService->createUser($data['email'], $data['roles'], $data['password'], $data['first_name'], $data['last_name'], $data['phone'], $data['verify'], $data['society']);

        return $this->json(HttpResponseHelper::success("Utilisateur créee"));
    }

    #[Route('/api/updateUser/{id}', name: 'updateUser', methods: ["PUT"])]
    public function updateUser(int $id, Request $request, UserService $userService): JsonResponse
    {
        $request = json_decode($request->getContent(), true);
        $response = $userService->updateUser($id, $request);
        return $this->json(HttpResponseHelper::success($response));

    }

    #[Route("/api/getUser/{id}", name: "readUser")]
    public function readUser(int $id, UserService $userService): JsonResponse
    {
        $response = $userService->getUser($id);

        return $this->json(HttpResponseHelper::success($response));
    }
}
