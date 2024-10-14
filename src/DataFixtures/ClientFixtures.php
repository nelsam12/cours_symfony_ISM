<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       
        // Création de 100 clients aléatoires
        for ($i = 1; $i <= 50; $i++) {
           
            $client = new Client();
            $client->setSurname('Nom'. $i);
            $client->setTelephone('77100101'. $i);//Trop long
            $client->setAdresse('Adresse'. $i);
            if ($i % 2 == 0) {
                $user = new User();
                $user->setNom('Nom'. $i);
                $user->setPrenom('Prenom'. $i);
                $user->setLogin('login'. $i);
                $user->setPassword('password'. $i);
                $client->setUser($user);
            }
            $manager->persist($client);
        }

        // Sauvegarde des données dans la base de données

        $manager->flush(); // Commit
    }
}
