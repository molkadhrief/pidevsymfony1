<?php

// src/Controller/MailerController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\EmailType;

class MailerController extends AbstractController
{
    #[Route('/email/{user}' , name: 'app_send')]
    public function sendEmail(Request $request, MailerInterface $mailer,$user): Response
    {
        $form = $this->createForm(EmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from('hello@example.com')
                ->to($user)
                ->subject($data['subject'])
                ->text($data['text']);

            $mailer->send($email);

            $this->addFlash('success', 'Email sent successfully!');

            return $this->redirectToRoute('app_reclamation_back_index'); 
        }

        return $this->render('mailer/index.html.twig', [
            'form' => $form->createView(), "username" => $user
        ]);
    }
}


