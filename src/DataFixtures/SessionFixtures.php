<?php

namespace App\DataFixtures;

use App\Entity\Session;
use App\Entity\Operateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // récupère tous les opérateurs existants
        $operateurs = $manager->getRepository(Operateur::class)->findAll();

        foreach ($operateurs as $operateur) {
            // pour chaque jour ouvré d'avril 2025
            for ($day = 1; $day <= 30; $day++) {
                $date = new \DateTime("2025-04-$day");

                // skip weekends
                if (in_array($date->format('N'), [6, 7])) continue;

                // Matin (8h - 12h)
                $sessionMatin = new Session();
                $sessionMatin->setOperateur($operateur);
                $debutMatin = (clone $date)->setTime(8, 0);
                $finMatin = (clone $date)->setTime(12, 0);
                $sessionMatin->setDebut($debutMatin);
                $sessionMatin->setFin($finMatin);
                $sessionMatin->setMessagesEnvoyes($faker->numberBetween(10, 50));
                $sessionMatin->setMessagesRecus($faker->numberBetween(10, 50));
                $manager->persist($sessionMatin);

                // Après-midi (13h30 - 17h30)
                $sessionAprem = new Session();
                $sessionAprem->setOperateur($operateur);
                $debutAprem = (clone $date)->setTime(13, 30);
                $finAprem = (clone $date)->setTime(17, 30);
                $sessionAprem->setDebut($debutAprem);
                $sessionAprem->setFin($finAprem);
                $sessionAprem->setMessagesEnvoyes($faker->numberBetween(10, 50));
                $sessionAprem->setMessagesRecus($faker->numberBetween(10, 50));
                $manager->persist($sessionAprem);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OperateurFixtures::class,
        ];
    }
}
