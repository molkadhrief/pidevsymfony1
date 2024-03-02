<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Vote;
use App\Entity\Post;
use App\Repository\PostRepository;

#[Route('/vote')]
class VoteController extends AbstractController
{
    #[Route('/new', name: 'app_vote', methods: ['POST'])]
    public function new(Request $request,PostRepository $postrepo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenValue');
            if ($this->isCsrfTokenValid('create-item', $token)) {
                $post = $postrepo->find($request->request->get('post_id'));
                if($post == null){
                    $dataReponse=['status'=>400,'error'=>'post doesn t exist'];
                    $this->addFlash('error', 'error happened');
                    return $this->json($dataReponse);
                }
                $vote = new Vote();
                $vote_type = $request->request->get('vote_type');
                if($vote_type == "down"){
                    $post->setDownVoteNum($post->getDownVoteNum()+1);
                    $vote->setDown(true);
                    $vote->setUp(false);
                }else if($vote_type == "up"){
                    $post->setUpVoteNum($post->getUpVoteNum()+1);
                    $vote->setDown(false);
                    $vote->setUp(true);
                }else{
                    $dataReponse=['status'=>400,'error'=>'vote type error'];
                    $this->addFlash('error', 'error happened');
                    return $this->json($dataReponse);
                }
                $vote->setUser("hech");
                $vote->setPost($post);
                $entityManager->persist($post);
                $entityManager->flush();
                $entityManager->persist($vote);
                $entityManager->flush();
                $dataReponse=['status'=>201,'post_id'=>$post->getId()];
                $this->addFlash('success', 'succes vote!');
                return $this->json($dataReponse);



            }
        }else{
            $dataReponse=['status'=>400,'error'=>'token is wron'];
            $this->addFlash('error', 'error happened');
            return $this->json($dataReponse);
        }
            $dataReponse=['status'=>400,'code'=>'only post method are allowed'];
            $this->addFlash('error', 'error happened');
            return $this->json($dataReponse);  
    }
}
