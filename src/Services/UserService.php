<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\User;

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


}