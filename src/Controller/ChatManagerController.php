<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatManagerController extends AbstractController
{
    #[Route('/chat/manager', name: 'app_chat_manager')]
    public function index(): Response
    {
        return $this->render('chat_manager/index.html.twig', [
            'controller_name' => 'ChatManagerController',
        ]);
    }
    #[Route('/chat/manager/{id}', name: 'app_chat_manager_between')]
    public function talkToSpecialist(): Response
    {
        return $this->render('chat_manager/index.html.twig', [
            'controller_name' => 'ChatManagerController',
        ]);
    }
}
