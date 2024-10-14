<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Dette;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        // Injection par consecteur
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {

        // Création de 100 clients aléatoires
        for ($i = 1; $i <= 50; $i++) {

            $client = new Client();
            $client->setSurname('Nom' . $i);
            $client->setTelephone('77100101' . $i); //Trop long
            $client->setAdresse('Adresse' . $i);

            if ($i % 2 == 0) {
                // Création d'un utilisateur et association avec le client
                $user = new User();
                $user->setNom('Nom' . $i);
                $user->setPrenom('Prenom' . $i);
                $user->setLogin('login' . $i);
                $plaintextPassword = "password";

                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $this->encoder->hashPassword(
                    $user, // doit implémenter l'interface
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $client->setUser($user);

                // Création des dettes
                for ($j = 1; $j <= 2; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(150000 * $j);
                    $dette->setMontantVerser(150000 * $j);
                    $client->addDette($dette);
                }
            }
            else{
                // Création d'un client sans utilisateur

                // Création des dettes non Soldées
                for ($j = 1; $j <= 2; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(150000 * $j);
                    $dette->setMontantVerser(150000);
                    $client->addDette($dette);
                }
            }
            $manager->persist($client);
        }

        // Sauvegarde des données dans la base de données

        $manager->flush(); // Commit
    }
}
