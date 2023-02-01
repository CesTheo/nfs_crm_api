<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use App\Helper\HttpResponseHelper;
use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function search(array $searchParams)
    {
        // - Select pagination params from array and remove them from the search params array
        $paginate = $searchParams['paginate'] ?? [];
        $limit = $paginate['limit'];
        $page = $paginate['page'];
        unset($searchParams['paginate']);

        // - Count total of rows
        $count = $this->userRepository->createQueryBuilder('o')
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // - Get paginated data from repository
        $offset = ($page - 1) * $limit;
        $users = $this->userRepository->findBy($searchParams, null, $limit, $offset);

        // - Convert entities to array to avoid circular reference issues
        for ($i = 0; $i < count($users); $i++) {
            $users[$i] = $users[$i]->toArray();
        }

        return [
            'users' => $users,
            'metadata' => [
                'page' => $page,
                'limit' => $limit,
                'pages' => $count > 0 ? ceil($count / $limit) : 1,
                'total' => $count
            ]
        ];
    }

    public function createUser(CreateUserRequestDto $dto) {
        if($this->alreadyExist($dto->email)) {
            throw HttpResponseHelper::badRequest("L'email est déja enregistré");
        }

        $user = new User();
        $user->setEmail($dto->email);
        $user->setRoles($dto->roles);
        $user->setPassword(password_hash($dto->password, PASSWORD_DEFAULT));
        $user->setFirstName($dto->firstName);
        $user->setLastName($dto->lastName);
        $user->setPhone($dto->phone);
        $user->setVerify($dto->verify);
        $user->setSociety($dto->society);
    
        $user = $this->userRepository->save($user);

        return $user;
    }

    public function alreadyExists(string $email): bool {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user != null;
    }
}