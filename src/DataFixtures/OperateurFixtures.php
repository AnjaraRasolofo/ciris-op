<?php

// src/DataFixtures/OperateurFixtures.php

namespace App\DataFixtures;

use App\Entity\Operateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OperateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $operateurs = [
            ['nom' => 'RAZANATONIA Hanitriniaina', 'prenom' => 'Irène', 'email' => 'zanatoniairene@gmail.com', 'telephone' => '+261 34 71 920 01'],
            ['nom' => 'LINA Marie', 'prenom' => 'Andréa', 'email' => 'linamarieandrea3@gmail.com', 'telephone' => '+261 34 67 275 94'],
            ['nom' => 'MIHAJAFITIAVANA Eusèbe', 'prenom' => 'Minohandrianina', 'email' => 'mihajafitiavana.handrianina@gmail.com', 'telephone' => '+261 33 78 428 86'],
            ['nom' => 'AINARIVELO Herimanitra', 'prenom' => 'Jussia', 'email' => 'herimanitra2003@gmail.com', 'telephone' => '+261 34 18 690 25'],
            ['nom' => 'NASOLONJANAHARY Miraniaina', 'prenom' => 'Estelle', 'email' => 'miraniainaestelle@gmail.com', 'telephone' => '+261 38 55 926 62'],
            ['nom' => 'RASOLOFOMANANA Perle', 'prenom' => 'Fitiavana', 'email' => 'perlerare866@gmail.com', 'telephone' => '+261 38 99 872 00'],
            ['nom' => 'ANJARASOA Nomenjanahary', 'prenom' => 'Emerencienne', 'email' => 'emerencienne@gmail.com', 'telephone' => '+261 38 96 002 78'],
            ['nom' => 'RAKOTOMANANDRAIBE Falimanana', 'prenom' => 'jenny', 'email' => 'jennyfalimanana@gmail.com', 'telephone' => '+261 34 34 274 82'],
            ['nom' => 'HANTANIRINA Joséphine', 'prenom' => 'Natacha', 'email' => 'brayanatasha@gmail.com', 'telephone' => '+261 34 37 703 73'],
            ['nom' => 'RAMANANTENASOA Juvena', 'prenom' => 'Rodin', 'email' => 'sylvioraveloson@gmail.com', 'telephone' => '+261 38 82 659 45'],
            ['nom' => 'NIRINA Raveloson', 'prenom' => 'Sylviana', 'email' => 'syssyh.rvlson@gmail.com', 'telephone' => '+261 34 64 767 28'],
            ['nom' => 'RAKOTOHARINAIVO Simeone', 'prenom' => 'Lalah', 'email' => 'lalah@gmail.com', 'telephone' => '+261 34 93 451 47'],
            ['nom' => 'RABESOA Brillant', 'prenom' => 'Victor', 'email' => 'evansbrilliant@gmail.com', 'telephone' => '+261 34 73 359 97'],
            ['nom' => 'RAZANAMAMPIONINA', 'prenom' => 'Elyjah', 'email' => 'elysjah@gmail.com', 'telephone' => '+261 38 53 378 71'],
            ['nom' => 'RANDRIANATOANDRO Nomenjanahary', 'prenom' => 'Faneva', 'email' => 'falyfaneva35@gmail.com', 'telephone' => '+261 34 88 751 22'],
            ['nom' => 'RAZANAMASY', 'prenom' => 'Louis', 'email' => 'louis75@gmail.com', 'telephone' => '+261 38 70 300 23'],
            ['nom' => 'TOMBOZAVELO Dios', 'prenom' => 'Foster', 'email' => 'dtombozavelo@gmail.com', 'telephone' => '+261 34 44 343 61'],
            ['nom' => 'SETA Nirina', 'prenom' => 'Judicaël', 'email' => 'judicaelseta@gmail.com', 'telephone' => '+261 34 66 696 29'],
            ['nom' => 'RAKOTOARINJAONA Maminirina', 'prenom' => 'Sylvie', 'email' => 'maminirina29.sylvie@gmail.com', 'telephone' => '+261 34 74 616 47'],
            ['nom' => 'BAGGIO', 'prenom' => 'Michel', 'email' => 'michelbaggio172@gmail.com', 'telephone' => '+261 34 31 317 54'],
        ];
        $postes = ['Téléconseiller', 'Superviseur', 'Technicien', 'Chargé de clientèle'];
        $statuts = ['actif', 'inactif'];

        for ($i = 1; $i < 20; $i++) {

            $operateur = new Operateur();
            //$this->addReference('operateur_' . $i, $operateur);
            $operateur->setNom($operateurs[$i - 1]['nom']);
            $operateur->setPrenom($operateurs[$i - 1]['prenom']);
            $operateur->setEmail($operateurs[$i - 1]['email']);
            $operateur->setTelephone($operateurs[$i - 1]['telephone']);
            $operateur->setMatricule('MAT-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT));
            $operateur->setDateEmbauche($faker->dateTimeBetween('-5 years', 'now'));
            $operateur->setPoste($faker->randomElement($postes));
            $operateur->setStatus($faker->randomElement($statuts));

            $manager->persist($operateur);
        }

        

        $manager->flush();
    }
}

