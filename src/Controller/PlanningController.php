<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\OperateurRepository;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

final class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository, OperateurRepository $operateurRepository, Request $request): Response
    {
        $type = $request->query->get('type');
        $operateurId = $request->query->get('operateur');

        // Pagination
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $planningRepository->createQueryBuilder('p');

        if ($type) {
            $qb->andWhere('p.type = :type')
            ->setParameter('type', $type);
        }

        if ($operateurId) {
            $qb->andWhere('p.operateur = :operateur')
            ->setParameter('operateur', $operateurId);
        }

        $qb->orderBy('p.debut', 'DESC');

        $total = count($qb->getQuery()->getResult());
        $plannings = $qb->setFirstResult($offset)->setMaxResults($limit)->getQuery()->getResult();
        $totalPages = ceil($total / $limit);
        
        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
            'operateurs' => $operateurRepository->findAll(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/planning/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planning);
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/planning/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/planning/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/planning/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, PlanningRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $planning = $repo->find($id);

        if (!$planning) {
            throw $this->createNotFoundException('Planning introuvable.');
        }

        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/plannings/export/pdf', name: 'app_planning_export_pdf')]
    public function exportPdf(PlanningRepository $planningRepository): Response
    {
        $plannings = $planningRepository->findAll();

        $html = $this->renderView('planning/pdf.html.twig', [
            'plannings' => $plannings,
        ]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="plannings.pdf"',
            ]
        );
    }

    #[Route('/planning-op', name: 'app_planning_cards', methods: ['GET'])]
    public function planningCards(PlanningRepository $planningRepository): Response
    {
        // Récupère tous les plannings triés par date
        $plannings = $planningRepository->findBy([], ['debut' => 'ASC']);

        // Regrouper les plannings par opérateur
        $operateursGroupes = [];

        foreach ($plannings as $planning) {
            $operateurId = $planning->getOperateur()->getId();

            if (!isset($operateursGroupes[$operateurId])) {
                $operateursGroupes[$operateurId] = [
                    'prenom' => $planning->getOperateur()->getPrenom(),
                    'plannings' => []
                ];
            }

            // Ajouter ce planning à l'opérateur
            $operateursGroupes[$operateurId]['plannings'][] = $planning;
        }

        return $this->render('planning/cards.html.twig', [
            'operateursGroupes' => $operateursGroupes,
        ]);
    }
}


