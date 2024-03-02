<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository)
    {

    }
    #[Route('/back', name: 'app_reclamation_back_index', methods: ['GET'])]
    public function backReclamation(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/dashboardReclamation.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if($this->validateReclamationData($reclamation)){
                $reclamation->SetCreatedAt(new \DateTimeImmutable() );
                $entityManager->persist($reclamation);
                $entityManager->flush();
                $this->addFlash('success', 'your message is sent!');
                return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('danger', 'An error occurred: ' );
                return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }



    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $this->addFlash('success', 'your message is sent!');
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_back_index', [], Response::HTTP_SEE_OTHER);
    }

    function validateReclamationData($data) {
        $validator = Validation::createValidator();
    
        $constraints = [
            'name' => [
                new NotBlank(['message' => 'Name should have at least one character.']),
                new Length(['min' => 1, 'minMessage' => 'Name should have at least one character.']),
            ],
            'phonNumber' => [
                new NotBlank(['message' => 'Phone number should have at least one character.']),
                new Length([
                    'min' => 1,
                    'max' => 15,
                    'minMessage' => 'Phone number should have at least one character.',
                    'maxMessage' => 'Phone number should have at most 15 characters.',
                ]),
                new Regex([
                    'pattern' => '/^00\d+$/',
                    'message' => 'Phone number should start with "00" and contain only digits.',
                ]),
            ],
            'email' => [
                new NotBlank(['message' => 'Email should have at least one character.']),
                new Email(['message' => 'This is not a valid email address.']),
                new Regex([
                    'pattern' => '/^[a-zA-Z0-9._%+-]+@gmail\.[a-zA-Z]{2,}$/',
                    'message' => 'Email should be in the format something@gmail.something.',
                ]),
            ],
            'titre' => [
                new NotBlank(['message' => 'Title should have at least one character.']),
                new Length(['min' => 1, 'minMessage' => 'Title should have at least one character.']),
            ],
            'message' => [
                new NotBlank(['message' => 'Message should have at least one character.']),
                new Length(['min' => 1, 'minMessage' => 'Message should have at least one character.']),
            ],
        ];
    
        foreach ($constraints as $field => $fieldConstraints) {
            $getterMethod = 'get' . ucfirst($field);
            $violations = $validator->validate($data->$getterMethod(), $fieldConstraints);
            if (count($violations) > 0) {
                return false;
            }
        }
    
        return true;
    }

}
