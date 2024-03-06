<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\event;
use App\Form\eventType;
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
}
