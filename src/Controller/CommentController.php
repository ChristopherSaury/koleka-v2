<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController{
    #[Route('/commentaire/article/{id}', name:'article_com', methods:'GET')]
    public function getAllArticleComment($id, CommentRepository $comment, SerializerInterface $serializer){
        $comment_article = $comment->getArticleComment($id);

        return $this->render('legend/comment.html.twig', [
            'comments' => $comment_article 
        ]);
    }
    #[Route('/commentaire/ajouter/{id}', name:'ajouter_com')]
    #[IsGranted('ROLE_USER')]
    public function addComment($id, EntityManagerInterface $em, CommentRepository $comment, ArticleRepository $article){
        if(!empty($_POST['comment-input'])){
            
            $new_comment = new Comment;
            
            $new_comment->setParent(null);
            $new_comment->setContent($_POST['comment-input']);
            $new_comment->setAuthor($this->getUser());
            $new_comment->setPublishedAt(new DateTime());
            $new_comment->setArticle($article->find($id));

            $em->persist($new_comment);
            $em->flush();

            return new Response(200);            
        }else{
            return new Response(500);
        }
    }
    
    #[Route('/commentaire/ajouter/reply/{id}', name:'ajouter_com_reply')]
    #[IsGranted('ROLE_USER')]
    public function addReplyComment($id, EntityManagerInterface $em, CommentRepository $comment, ArticleRepository $article){
        if(!empty($_POST['reply-input']) && $_POST['comment_parentid']){
            $new_comment = new Comment;
            
            $new_comment->setParent($comment->find($_POST['comment_parentid']));
            $new_comment->setContent($_POST['reply-input']);
            $new_comment->setAuthor($this->getUser());
            $new_comment->setPublishedAt(new DateTime());
            $new_comment->setArticle($article->find($id));

            $em->persist($new_comment);
            $em->flush();

            return new Response(200);
        }else{
            return new Response(500);
        }
    }

    #[Route('/commentaire/modifier/{id}', name:'modifier_commentaire', methods:'GET')]
    #[IsGranted('ROLE_USER')]
    public function updateComment($id , CommentRepository $comment, SerializerInterface $serializer){
        $update_com = $comment->find($id);
        $json = $serializer->serialize($update_com, 'json', ['groups' => 'com:read']);
        $response = new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
        return $response;
    }

    #[Route('/commentaire/modifier/handler/{id}', name:'modifier_com_handler')]
    #[IsGranted('ROLE_USER')]
    public function updateCommentHandler(EntityManagerInterface $em, Comment $comment){
        if(!empty($_POST['update-input'])){
            $comment->setContent($_POST['update-input']);
            $em->persist($comment);
            $em->flush();

            return new Response(200);
        }else{
            return new Response(400);
        }
    }

    #[Route('/commentaire/supprimer/{id}', name:'supprimer_commentaire')]
    #[IsGranted('ROLE_USER')]
    public function deleteComment(  $id ,CommentRepository $repo){
        if($id){
            $repo->deleteCom($id);
    
            return new Response(200);
        }else{
            return new Response(404);
        }
    }
}