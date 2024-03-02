<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    
    #[Route('/', name: 'app_post_index', methods: ['GET','POST'])]
    public function index(PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->getOnlyApprovedPost(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/back', name: 'app_post_back_index', methods: ['GET','POST'])]
    public function backPosts(PostRepository $postRepository): Response
    {
        return $this->render('post/dashboardPosts.html.twig', [
            'posts' => $postRepository->getOnlyPost(),
        ]);
    }
    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenValue');
            if ($this->isCsrfTokenValid('create-item', $token) && $request->request->get('post_title') != "" && $request->request->get('post_description') != "")  {
                $post = new Post();
                $post->setTitle($request->request->get('post_title'));
                $post->setDescription($request->request->get('post_description'));
                $post->setApproved(false);
                $post->setBigPost(true);
                $post->setDownVoteNum(0);
                $post->setUpVoteNum(0);
                $post->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($post);
                $entityManager->flush();
                $dataReponse=['status'=>201,'post_id'=>$post->getId()];
                $this->addFlash('succes', 'your post is created ,wait for the approval!');
                return $this->json($dataReponse);
            }else{
                $dataReponse=['status'=>400,'error'=>'there is a problem in post creating'];
                $this->addFlash('danger', 'there is an error!');
                return $this->json($dataReponse);
            }
        }else{
            $this->addFlash('danger', 'there is an error problem in post creating !');
            $dataReponse=['status'=>400,'code'=>'only post method are allowed'];
            return $this->json($dataReponse);  
        }

    }

    #[Route('/newcomment', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function newcomment(Request $request,PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenValue');
            if ($this->isCsrfTokenValid('create-item', $token) && $request->request->get('Comment_description') != "") {
                $post = new Post();
                $post->setTitle("comment");
                $post->setDescription($request->request->get('Comment_description'));
                $post->setApproved(true);
                $greatPost = $postRepository->find($request->request->get('post_id'));
                $post->setPost($greatPost);
                $post->setBigPost(false);
                $post->setDownVoteNum(0);
                $post->setUpVoteNum(0);
                $post->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($post);
                $entityManager->flush();
                $dataReponse=['status'=>201,'post_id'=>$post->getId()];
                $this->addFlash('success', 'created successful!');
                return $this->json($dataReponse);
            }else{
                $dataReponse=['status'=>400,'error'=>'there is  a'];
                $this->addFlash('danger', 'comment not created');
                return $this->json($dataReponse);
            }
        }else{

            $dataReponse=['status'=>400,'code'=>'only post method are allowed'];
            return $this->json($dataReponse);  
        }

    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $post->getDescription() != "" && $post->getTitle() != 0) {
            $entityManager->flush();
            $this->addFlash('success', 'Update successful!');
            if($post->getPost() == null){
                return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
            }else if( $post->getPost() != null && $post->getPost()->getPost() == null ){
                return $this->redirectToRoute('app_post_show', ['id' => $post->getPost()->getId()]);
            }else if($post->getPost() != null && $post->getPost()->getPost() != null){
                return $this->redirectToRoute('app_post_show', ['id' => $post->getPost()->getPost()->getId()]);
            }
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
    #[Route('/approve/{id}', name: 'app_post_back_edit', methods: ['GET', 'POST'])]
    public function editApprove(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('update-item', $request->request->get('approve_token'))) {
            $post->setApproved(true);
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'approved post!');
            return $this->redirectToRoute('app_post_back_index');
        }
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRemove = $post ;
            foreach ($postRemove->getComment() as $comment) {
                if($comment->getComment() != null)
                {
                    foreach ($comment->getComment() as $respond) {
                        $entityManager->remove($respond);
                    }
                }
                $entityManager->remove($comment);
            }
            $entityManager->remove($postRemove);
            $entityManager->flush();
            $this->addFlash('success', 'item delted !');
            if($request->headers->get('referer') == "http://127.0.0.1:8000/post/back" || $request->headers->get('referer') == "http://127.0.0.1:8000/post/back/"){
                return $this->redirectToRoute('app_post_back_index');
            }
            if( $post->getPost() != null && $post->getPost()->getPost() == null ){
                return $this->redirectToRoute('app_post_show', ['id' => $post->getPost()->getId()]);
            }elseif($post->getPost() != null && $post->getPost()->getPost() != null){
                return $this->redirectToRoute('app_post_show', ['id' => $post->getPost()->getPost()->getId()]);
            }
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

}
