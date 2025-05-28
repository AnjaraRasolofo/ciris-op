<?php

namespace App\Command;

use App\Entity\Session;
use App\Repository\OperateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-connexion-sessions',
    description: 'Add a short description for your command',
)]
class GenerateConnexionSessionsCommand extends Command
{
    public function __construct(OperateurRepository $operateurRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->operateurRepository = $operateurRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $operateurs = $this->operateurRepository->findAll();

        shuffle($operateurs); // Mélange aléatoire

        $count = random_int(1, min(19, count($operateurs)));

        $actifs = array_slice($operateurs, 0, $count);

        foreach ($actifs as $operateur) {
            $session = new Session();
            $session->setOperateur($operateur);
            $session->setActif(true);
            $session->setHeureConnexion(new \DateTime());

            $this->em->persist($session);
        }

        $this->em->flush();

        $output->writeln(" $count sessions créées pour aujourd’hui.");

        return Command::SUCCESS;
    }
}
