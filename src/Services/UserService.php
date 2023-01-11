<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use App\Helper\HttpResponseHelper;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
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
    
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return true;
    }

    public function alreadyExist($email){
        $users = $this->doctrine->getRepository(User::class);
        $user = $users->findOneBy(['email' => $email]);

        if($user === null){
            return true;
        }else{
            throw new BadRequestHttpException("L'email est déja enregistrer");
        }
    }

    public function getAllUser(){

        $repository = $this->doctrine->getRepository(User::class);
        $users = $repository->findAll();

        // Convertir chaque objet User en un tableau car référence circulaire 
        $usersArray = array();
        foreach ($users as $user) {
            $usersArray[] = $user->toArray();
        }

        return $usersArray;
    }

    public function updateUser($id, $data){

        if (!isset($data['email']) || !isset($data['roles']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name']) || !isset($data['phone']) || !isset($data['verify']) || !isset($data['society'])) {
            throw new BadRequestHttpException("Requete invalide");
        }

        $repository = $this->doctrine->getRepository(User::class);
        $user = $repository->findOneById($id);

        if(!$user){
            throw new BadRequestHttpException("L'utilisateur est introuvable");
        }

        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setPhone($data['phone']);
        $user->setVerify($data['verify']);
        $user->setSociety($data['society']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return("L'utilisateur a bien était modifier");
    }

    public function getUser($id){
        $repository = $this->doctrine->getRepository(User::class);
        $user = $repository->find($id);
        if (!$user) {
            throw new BadRequestHttpException("User introuvable");
        }
        return($user->toArray());
    }
}