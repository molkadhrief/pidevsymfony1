<?php

namespace App\Controller;

use App\Entity\Postimage;
use App\Repository\PostRepository;
use App\Form\PostimageType;
use App\Repository\PostimageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/postimage')]
class PostimageController extends AbstractController
{
    #[Route('/', name: 'app_postimage_index', methods: ['GET'])]
    public function index(PostimageRepository $postimageRepository): Response
    {
        return $this->render('postimage/index.html.twig', [
            'postimages' => $postimageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_postimage_new', methods: ['GET', 'POST'])]
    public function new(Request $request,PostRepository $postRepo , EntityManagerInterface $entityManager): Response
    {
        $postimage = new Postimage();
        if ($request->isMethod('POST')) {
            $postimage = new Postimage();
            $file = $request->files->get('postimage');
            
            if ($file){
                $post_id = $request->get('post_id');
                $filename = md5(uniqid('', true)) . '.' . $file->guessClientExtension();
                $file->move(
                    $this->getParameter(name:'upload_author'),$filename
                ) ;
                $postimage->setUrl($filename);
                if($postRepo->find($post_id) == null){
                    return $this->json(['status'=>'400','code'=>"post doesn't exist"]);
                };
                $postimage->setPost($postRepo->find($post_id));
                $entityManager->persist($postimage);
                $entityManager->flush();
                return $this->json(['status'=>'200','code'=>"image saved"]);
            }else{
                return $this->json(['status'=>'400','code'=>"file doesn't exist"]);
            }
        }

        $form = $this->createForm(PostimageType::class, $postimage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postimage);
            $entityManager->flush();

            return $this->redirectToRoute('app_postimage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('postimage/new.html.twig', [
            'postimage' => $postimage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_postimage_show', methods: ['GET'])]
    public function show(Postimage $postimage): Response
    {
        return $this->render('postimage/show.html.twig', [
            'postimage' => $postimage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_postimage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Postimage $postimage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostimageType::class, $postimage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_postimage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('postimage/edit.html.twig', [
            'postimage' => $postimage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_postimage_delete', methods: ['POST'])]
    public function delete(Request $request,PostimageRepository $postimageRepo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete-image', $request->request->get('tokenValue'))) {
            $postimage = new Postimage ;
            $postimage = $postimageRepo->find($request->request->get('image_id'));
            $entityManager->remove($postimage);
            $entityManager->flush();
            $data = ['status'=>200,"code"=>"image deleted"] ;
            return $this->json($data);
        }
        $data = ['status'=>400,"code"=>"image not deleted"] ;
        return $this->json($data);

        //return $this->redirectToRoute('app_postimage_index', [], Response::HTTP_SEE_OTHER);
    }
}
