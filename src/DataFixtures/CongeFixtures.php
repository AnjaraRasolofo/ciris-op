<?php

namespace App\DataFixtures;

use App\Entity\Conge;
use App\Entity\Operateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\DataFixtures\OperateurFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CongeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $operateurs = $manager->getRepository(Operateur::class)->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $conge = new Conge();

            // On récupère une référence à un opérateur
            $operateur = $operateurs[array_rand($operateurs)];

            $dateDebut = $faker->dateTimeBetween('-1 month', '+1 month');
            $dateFin = (clone $dateDebut)->modify('+'.rand(3, 10).' days');

            $conge->setOperateur($operateur);
            $conge->setDebut($dateDebut);
            $conge->setFin($dateFin);
            $conge->setMotif($faker->randomElement([
                'Congé annuel', 'Congé maladie', 'Formation', 'Congé sans solde'
            ]));

            $manager->persist($conge);
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
