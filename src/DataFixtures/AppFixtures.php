<?php
namespace App\DataFixtures;

use App\Entity\Fiche;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $user = new User();
        $user->setEmail("dev@dev.com");
        $user->setFirstName($faker->firstName());
        $user->setLastName($faker->lastName());
        $user->setPassword('$2y$13$MfQr17U8/uD.bOGJMnsTpeB3rMRn5onqhEniKj3woP6RuLUWhMfRG');
        $user->setPhone($faker->mobileNumber());
        $user->setVerify("false");
        $user->setSociety($faker->randomElement(['Youtube', 'Paypal', 'Amazon', 'NeedForSchool']));
        $manager->persist($user);


        for ($i=0; $i < 5; $i++) { 
            $user = new User();
            $user->setEmail($faker->email());
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setPassword(password_hash($faker->password, PASSWORD_DEFAULT));
            $user->setPhone($faker->mobileNumber());
            $user->setVerify("false");
            // $user->setCreatedAt($faker->dateTime());
            // $user->setUpdatedAt($faker->dateTime());
            $user->setSociety($faker->randomElement(['Youtube', 'Paypal', 'Amazon', 'NeedForSchool']));
            for ($x=0; $x < 5; $x++) { 
                $fiche = new Fiche();
                $fiche->setUser($user);
                $fiche->setName($faker->sentence(5));
                $fiche->setCategorie($faker->randomElement(['devis', 'facture']));
                $fiche->setData($faker->paragraphs(5));
                // if($faker->shuffle([0, 1])){
                //     $historyleads = new HistoryPaiment();
                //     $historyleads->setFicheId($fiche);
                //     $historyleads->setIsPaid(true);
                //     $historyleads->setExecuteAt($faker->dateTime());
                //     $historyleads->setPrice($faker->numberBetween(60, 75000));
                //     $manager->persist($historyleads);
                // }

                $manager->persist($fiche);
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
