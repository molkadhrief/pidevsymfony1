<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Logement;
use App\Form\LogementType;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BackofficeController extends AbstractController
{
    #[Route('/back', name: 'app_backoffice')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackofficeController',
        ]);
    }

    #[Route('/categoriesb', name: 'categoriesb')]
    public function categoriesb(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
        return $this->render('back/categories.html.twig', [
            'categories' => $categories,
            'controller_name' => 'BackofficeController',
        ]);
    }
    #[Route('/logementsb', name: 'logementsb')]
    public function logementsb(EntityManagerInterface $entityManager): Response
    {
        $logements = $entityManager
            ->getRepository(Logement::class)
            ->findAll();

        return $this->render('back/logements.html.twig', [
            'logements' => $logements,
            'controller_name' => 'BackofficeController',
        ]);
    }
}
