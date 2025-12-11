<?php

namespace App\DataFixtures;

use App\Entity\Tuteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TuteurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void {
        $tuteur = new Tuteur();
        $tuteur->setNom('Admin');
        $tuteur->setPrenom('Super');
        $tuteur->setEmail('admin@example.com');
        $tuteur->setTelephone('0600000000');
        $tuteur->setPassword('password'); 

        $manager->persist($tuteur);
        $manager->flush();
    }
}
