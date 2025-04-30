<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Repository\CongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conges')]
final class CongeController extends AbstractController
{
    #[Route(name: 'app_conge_index', methods: ['GET'])]
    public function index(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }

    #[Route('/en-attente', name: 'app_conge_en_attente', methods: ['GET'])]
    public function congeEnAttente(CongeRepository $congeRepository): Response
    {
        $congesEnAttente = $congeRepository->findCongesEnAttente();

        return $this->render('conge/pending.html.twig', [
            'congesEnAttente' => $congesEnAttente,
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conge = new Conge();
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conge);
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_show', methods: ['GET'])]
    public function show(Conge $conge): Response
    {
        return $this->render('conge/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_delete', methods: ['POST'])]
    public function delete(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/conge/{id}/update', name: 'update_conge_status', methods: ['POST'])]
    public function updateStatus(int $id, Request $request, CongeRepository $congeRepo, EntityManagerInterface $em): Response
    {
        $conge = $congeRepo->find($id);

        if (!$conge) {
            throw $this->createNotFoundException("Congé introuvable");
        }

        //$this->denyAccessUnlessGranted('ROLE_ADMIN'); // optionnel : sécurisation

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('update_conge_' . $id, $token)) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $newStatus = $request->request->get('status');
        if (in_array($newStatus, ['en attente', 'approuvé', 'rejecté'])) {
            $conge->setStatus($newStatus);
            $em->flush();
        }

        return $this->redirectToRoute('app_conge_en_attente');
    }
}
