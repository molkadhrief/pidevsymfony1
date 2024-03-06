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
use App\Repository\UserRepository;



class BackofficeController extends AbstractController
{
    
    
    #[Route('/back', name: 'app_backoffice')]
    public function index(UserRepository $userRepository): Response
    {   
        $totalUsers = $userRepository->count([]);
        $activatedUsers = $userRepository->count(['isActivated' => true]);
        $adminUsersCount = $userRepository->countByRoleAdmin('ROLE_ADMIN');

        
        
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackofficeController',
            'totalUsers' => $totalUsers,
            'activatedUsers' => $activatedUsers,
            'adminUsersCount' => $adminUsersCount,
            
        ],);
    }

   
}
