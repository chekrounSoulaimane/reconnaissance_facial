<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;

class UtilisateurFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $utilisateur1 = new Utilisateur();
        $utilisateur1->setCin('user1');
        $utilisateur1->setNom('Burrows');
        $utilisateur1->setPrenom('Lincoln');
        $utilisateur1->setAge(34);
        $utilisateur1->setAdresse('SYBA N 65 ,Derb chawech, Marrakech');

        $manager->persist($utilisateur1);

        $utilisateur2 = new Utilisateur();
        $utilisateur2->setCin('user2');
        $utilisateur2->setNom('Tancredi');
        $utilisateur2->setPrenom('Sara');
        $utilisateur2->setAge(26);
        $utilisateur2->setAdresse('SYBA N 12 ,Derb lgbss, Marrakech');

        $manager->persist($utilisateur2);
            
        $utilisateur3 = new Utilisateur();
        $utilisateur3->setCin('user3');
        $utilisateur3->setNom('Scofield');
        $utilisateur3->setPrenom('Micheal');
        $utilisateur3->setAge(28);
        $utilisateur3->setAdresse('SYBA N 65 ,Derb sraghna, Marrakech');
        
        $manager->persist($utilisateur3);

        $manager->flush();

        //php bin/console doctrine:fixtures:load
    }
}
