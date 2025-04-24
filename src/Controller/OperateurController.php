<?php

namespace App\Controller;

use App\Entity\Operateur;
use App\Form\OperateurType;
use App\Repository\OperateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/operateur')]
final class OperateurController extends AbstractController
{
    #[Route(name: 'app_operateur_index', methods: ['GET'])]
    public function index(OperateurRepository $operateurRepository): Response
    {
        return $this->render('operateur/index.html.twig', [
            'operateurs' => $operateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_operateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operateur = new Operateur();
        $form = $this->createForm(OperateurType::class, $operateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_operateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operateur/new.html.twig', [
            'operateur' => $operateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_operateur_show', methods: ['GET'])]
    public function show(Operateur $operateur): Response
    {
        return $this->render('operateur/show.html.twig', [
            'operateur' => $operateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_operateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operateur $operateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperateurType::class, $operateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_operateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operateur/edit.html.twig', [
            'operateur' => $operateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_operateur_delete', methods: ['POST'])]
    public function delete(Request $request, Operateur $operateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($operateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_operateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
