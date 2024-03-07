<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Vich\UploaderBundle\Handler\UploadHandler;




#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedPassword = $form->get('plainPassword')->getData();

        // Check if the submitted password matches the stored password
        if ($userPasswordHasher->isPasswordValid($user, $submittedPassword)) {
            // Passwords match, proceed with the edit
            // Save the user entity to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        } else {
            // Passwords don't match, display an error message to the user
            $this->addFlash('error', 'Incorrect password. Please enter the correct password.');

        }

        $imageFile = $form['imageFile']->getData();

        if ($imageFile) {
            // Generate a unique filename
            $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

            // Move the uploaded file to the upload directory
            $imageFile->move(
                $this->getParameter('image_directory'), // Get the directory path from parameters
                $fileName
            );

            // Set the image filename in the user entity
            $user->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        
            
            //$entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    // ACTIVATE USER   
    #[Route('/activate/{id}', name:'user_activate', methods:["POST"])]
    public function activateUser(User $user): RedirectResponse
    {
        // Set the isActivated attribute to true
        $user->setIsActivated(true);
        $this->getDoctrine()->getManager()->flush();

        // Return a JSON response indicating success
        $this->addFlash('success', 'User activated successfully.');
        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/deactivate/{id}', name:'user_deactivate', methods:["POST"])]
    public function deactivateUser( User $user): RedirectResponse
    {
        // Set the isActivated attribute to true
        $user->setIsActivated(false);
        $this->getDoctrine()->getManager()->flush();

        // Return a JSON response indicating success
        $this->addFlash('success', 'User deactivated successfully.');
        return $this->redirectToRoute('app_user_index');
        }

        
    
        public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
            $query = $request->query->get('query');
            $users = $entityManager->getRepository(User::class)->findBySearchQuery($query);

            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'id' => $user->getId(),
                    'fullName' => $user->getFullName(),
                    'email' => $user->getEmail(),
                    'adress' => $user->getAdress()
                ];
            }

        return $this->json($data);
}


    #[Route("/user/{id}/add-admin-role", name:"add_admin_role")]
     
    public function addAdminRole(User $user): Response
    {
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles(), true);

        // Toggle the admin role
        $roles = $user->getRoles();
        if ($isAdmin) {
            $key = array_search('ROLE_ADMIN', $roles);
            if ($key !== false) {
                unset($roles[$key]);
            }
        } else {
            array_unshift($roles, 'ROLE_ADMIN');
            
        }

        // Set the updated roles
        $user->setRoles($roles);

        // Persist changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redirect back to the user details page
        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }


    
    
}

