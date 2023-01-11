<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

    public function verifyUser($email, $roles, $password, $firstName, $lastName, $phone, $verify, $society){

        if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
            throw new BadRequestHttpException("Un champs n'a pas était bien remplie");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestHttpException("L'email invalide");
        }

        if (strlen($password) < 8) {
            throw new BadRequestHttpException("Le mot de passe est trop petit");
        }

        if (!preg_match('/^\d{10}$/', $phone)) {
            throw new BadRequestHttpException("Le numéro de téléphone n'est pas au bon format");            
        }

        if (!in_array($verify, [0, 1])) {
            throw new BadRequestHttpException("Erreur sur le 'verify'");
        }

        if (!is_array($roles) || count(array_intersect($roles, ['ROLE_ADMIN', 'ROLE_USER'])) !== count($roles)) {
            throw new BadRequestHttpException("Erreur sur le choix des ROLES");
        }

        if(empty($society)){
            throw new BadRequestHttpException("La societé n'a pas était bien remplie");
        }

        return true;
    }

    public function createUser($email, $roles, $password, $firstName, $lastName, $phone, $verify, $society){

        $this->alreadyExist($email);

        $this->verifyUser($email, $roles, $password, $firstName, $lastName, $phone, $verify, $society);

        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhone($phone);
        $user->setVerify($verify);
        $user->setSociety($society);
    
        $this->userRepository->save($user);

        return true;
    }

    public function alreadyExist($email){
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if($user === null){
            return true;
        }else{
            throw new BadRequestHttpException("L'email est déja enregistrer");
        }
    }
}