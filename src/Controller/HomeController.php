<?php

namespace App\Controller;

use App\Repository\OperateurRepository;
use App\Repository\SessionRepository;
use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(OperateurRepository $operateurRepository, SessionRepository $sessionRepository, CongeRepository $congeRepository): Response
    {

        $totalOperateurs = $operateurRepository->count([]);
        //$sessionsEnCours = $sessionRepository->count(['isActive' => true]);
        $totalMessagesEnvoyes = $sessionRepository->getTotalMessagesEnvoyes(); 
        $totalMessagesRecus = $sessionRepository->getTotalMessagesRecus();
        $totalCongesEnAttente = $congeRepository->countCongesEnAttente();
        //$notifications = $notificationRepository->count(['isRead' => false]);

        $stats = $sessionRepository->getStatsOfToday();
        
        return $this->render('home/index.html.twig', [
            'totalOperateurs' => $totalOperateurs,
            //'sessionsEnCours' => $sessionsEnCours,
            'totalMessagesEnvoyes' => $totalMessagesEnvoyes,
            'totalMessagesRecus' => $totalMessagesRecus,
            //'notifications' => $notifications,
            'totalCongesEnAttente' => $totalCongesEnAttente,
            'stats' => $stats
        ]);

    }
}
