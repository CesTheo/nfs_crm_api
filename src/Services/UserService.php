<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class UserService
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
    }

    public function verifyUser($email, $roles, $password, $firstName, $lastName, $phone, $verify, $society){

        if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (strlen($password) < 8) {
            return false;
        }

        if (!preg_match('/^\d{10}$/', $phone)) {
            return false;
        }

        if (!in_array($verify, [0, 1])) {
            return false;
        }

        if (!is_array($roles) || count(array_intersect($roles, ['ROLE_ADMIN', 'ROLE_USER'])) !== count($roles)) {
            return false;
        }

        if(empty($society)){
            return false;
        }

        return true;
    }

    public function createUser($email, $roles, $password, $firstName, $lastName, $phone, $verify, $society){
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
            return false;
        }
    }


}