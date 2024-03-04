<?php

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/logement')]
class LogementController extends AbstractController
{
    #[Route('/', name: 'app_logement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $logements = $entityManager
            ->getRepository(Logement::class)
            ->findAll();

        return $this->render('logement/index.html.twig', [
            'logements' => $logements,
        ]);
    }
    

    #[Route('/new', name: 'app_logement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $logement = new Logement();
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($logement);
            $entityManager->flush();

            return $this->redirectToRoute('logementsb', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('logement/new.html.twig', [
            'logement' => $logement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logement_show', methods: ['GET'])]
    public function show(Logement $logement): Response
    {
        return $this->render('logement/show.html.twig', [
            'logement' => $logement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_logement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('logementsb', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('logement/edit.html.twig', [
            'logement' => $logement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logement_delete', methods: ['POST'])]
    public function delete(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $logement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($logement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('logementsb', [], Response::HTTP_SEE_OTHER);
    }
}
