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
use App\Entity\User;


class BackofficeController extends AbstractController
{
    
    
    #[Route('/back', name: 'app_backoffice')]
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('App\Entity\User');
        
        // Count total users
        $totalUsers = $userRepository->createQueryBuilder('user')
            ->select('COUNT(user.id)')
            ->getQuery()
            ->getSingleScalarResult();
        
        


        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackofficeController',
        ],);
    }

   
}
