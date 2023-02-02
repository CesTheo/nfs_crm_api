<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\HttpResponseHelper;
use Symfony\Component\Serializer\SerializerInterface;
use App\Validation\Dtos\CreateUserRequestDto;
use App\Validation\CustomValidator;

#[Route('/users', name: 'users')]
class ApiUsersController extends AbstractController
{
    private UserService $userService;
    private CustomValidator $validator;
    private SerializerInterface $serializer;

    function __construct(UserService $userService, CustomValidator $validator, SerializerInterface $serializer)
    {
        $this->userService = $userService;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    #[Route('/me', name: '_me', methods: ['GET'])]
    function getDataUser(#[CurrentUser] ?User $user): JsonResponse
    {
        if(!$user){
            return HttpResponseHelper::notFound("Utilisateur non trouvé");
        }

        return $this->json(HttpResponseHelper::success($user->toArray()));
    }

    #[Route("/{id}", name: "_read", methods: ["GET"])]
    public function readUser(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->userService->search([
            'id' => $id
        ]);
        
        if (!$user) {
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

    #[Route('/create', name: '_create', methods: ["POST"])]
    function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = $this->serializer->denormalize($data, CreateUserRequestDto::class);
        $this->validator->validate($dto);
        $user = $this->userService->createUser($dto);

        return $this->json(HttpResponseHelper::success($user));
    }

    #[Route('/{id}', name: '_update', methods: ["PUT"])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = $this->serializer->denormalize($data, UpdateUserRequestDto::class);
        $errors = $this->validator->validate($dto);

        if(count($errors) > 0) {
            return HttpResponseHelper::badRequest("Il manque un ou plusieurs champs dans la requete", $errors);
        }

        $user = $this->userService->search([
            'id' => $id
        ]);

        if(!$user) {
            return HttpResponseHelper::notFound("Utilisateur non trouvé");
        }

        $user = $this->userService->updateUser($id, $dto);

        return $this->json(HttpResponseHelper::success($user));
    }
}
