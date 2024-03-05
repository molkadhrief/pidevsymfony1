<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class FrontofficeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {

        $user = $this->getUser();

        // Check if user is null or if the roles attribute is null
        if ($user === null || $user->getRoles() === null) {
            // Render home_visitor.html.twig for visitors with null roles
            return $this->render('front/home_visitor.html.twig', [
                'controller_name' => 'FrontofficeController',
            ]);
        }

        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontofficeController',
        ],);
    }

}
