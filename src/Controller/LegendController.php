<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CountryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class LegendController extends AbstractController{

    #[Route("/legende", name:"legende")]
    public function legende(CountryRepository $country){
        if($this->getUser() == true && $this->getUser()->isVerified() == false){
            return $this->redirectToRoute('identication_required');
        }
        return $this->render('legend/legend.html.twig',[
            'controller_name' => 'LegendController',
            'african_countries' => $country->displayAfricanCountry(),
            'american_countries' => $country->displayAmericanCountry(),
            'asian_countries' => $country->displayAsianCountry(),
            'european_countries' => $country->displayEuropeanCountry()
        ]);
    }

    #[Route("/legende/pays/{id}", name:"un_pays")]
    public function displayCountryPage($id, CountryRepository $country, ArticleRepository $article){
        $one_country = $country->find($id);
        if(!$one_country){
            return $this->redirectToRoute('error_404');
        }
        return $this->render('legend/country.html.twig',[
            'controller_name'=> 'LegendController',
            'country' => $one_country,
            'article' => $article->getCountryArticle($id)
        ]);
    }
    
    #[Route("legende/ajouter", name:"legende_creer")]
    #[IsGranted('ROLE_USER')]
    public function insertLegend(EntityManagerInterface $em, Request $request){
        $new_article = new Article;
        $new_article->setAuthor($this->getUser());
        $new_article->setPublishedAt(new DateTime());
        $form = $this->createForm(ArticleType::class, $new_article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($new_article);
            $em->flush();
             
            return $this->redirect($this->generateUrl('mes_articles', array('id' => $this->getUser()->getId())));
        }
        return $this->render('legend/create.html.twig',[
            'controller_name' => 'LegendController',
            'legendForm' => $form->createView(),
        ]);
    }

    #[Route("/legende/articles/user/{id}", name:"mes_articles")]
    #[IsGranted('ROLE_USER')]
    public function userArticle($id, ArticleRepository $article){
        if($this->getUser()->getId() != $id){
            return $this->redirectToRoute('error_403');
        } 
        return $this->render('legend/user-article.html.twig',[
            'user_articles' => $article->getUserArticle($id)
        ]);
    }
    #[Route("legende/article/{id}", name:"article")]
    public function displayArticle($id, ArticleRepository $article){
        $legend = $article->find($id);
        if(!$legend){
            return $this->redirectToRoute('error_404');
        }
        return $this->render('legend/article.html.twig',[
            'legend' => $legend
        ]);
    }

    #[Route("/legende/modifier/article/{id}", name:"modifier_article")]
    #[IsGranted("ROLE_USER")]
    public function updateArticle(Request $request, Article $article, EntityManagerInterface $em){
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){           

            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('mes_articles', array('id' => $this->getUser()->getId())));
        }else{
            return $this->render("legend/article-update.html.twig",[
                "updateLegend" => $form->createView()
            ]);
        }
    }
    #[Route("/legende/supprimer/{id}", name:"supprimer_article")]
    #[IsGranted("ROLE_USER")]
    public function deleteArticle( Article $article ,EntityManagerInterface $em){
        
        if($article->getIllustration()){
            $illustration = basename($article->getIllustration());
            unlink($this->getParameter('kernel.project_dir') . '/public/uploads/' . $illustration);
        }

        $em->remove($article);
        $em->flush();

        return $this->redirect($this->generateUrl('mes_articles', array('id' => $this->getUser()->getId())));

    }
}