<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Entity\Logement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class FrontofficeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontofficeController',
        ]);
    }

    #[Route('/logements', name: 'logements')]
    public function logements(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
    
        // Replace findAll() with a query that retrieves paginated results
        $query = $entityManager
            ->getRepository(Logement::class)
            ->createQueryBuilder('l')
            ->getQuery();
    
        $logements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number, default is 1
            2 // Number of items per page
        );
    
        return $this->render('front/logement.html.twig', [
            'categories' => $categories,
            'logements' => $logements,
            'controller_name' => 'FrontofficeController',
        ]);
    }
    #[Route('/catgories', name: 'catgories')]
    public function catgories(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
        $logements = $entityManager
            ->getRepository(Logement::class)
            ->findAll();
        return $this->render('front/catgorie.html.twig', [
            'categories' => $categories,
            'logements' => $logements,
            'controller_name' => 'FrontofficeController',
        ]);
    }
}
