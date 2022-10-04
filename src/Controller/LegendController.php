<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class LegendController extends AbstractController{
    #[Route("/legende", name:"legende")]
    public function legende(CountryRepository $country){
        
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
        return $this->render('legend/country.html.twig',[
            'controller_name'=> 'LegendController',
            'country' => $one_country,
            'article' => $article->getCountryArticle($id)
        ]);
    }
    
    #[Route("legende/ajouter", name:"legende_creer")]
    #[IsGranted('ROLE_USER')]
    public function insertLegend(EntityManagerInterface $em, Request $request , SluggerInterface $slugger){
        $new_article = new Article;
        $new_article->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $new_article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $img_file = $form->get('illustration')->getData();

            if($img_file){
                $originFileName = pathinfo($img_file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originFileName);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $img_file->guessExtension();

                try{
                    $img_file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e){
                    dd($e);
                }
                $new_article->setIllustration($newFilename);
            }

            $em->persist($new_article);
            $em->flush();
             
            return $this->redirectToRoute('accueil');
        }
        return $this->render('legend/create.html.twig',[
            'controller_name' => 'LegendController',
            'legendForm' => $form->createView(),
        ]);
    }
    #[Route("/legende/articles/user/{id}", name:"mes_articles")]
    #[IsGranted('ROLE_USER')]
    public function userArticle($id, ArticleRepository $article){
        //if($this->getUser()->getId() != $id) dd("error 403");
        return $this->render('legend/user-article.html.twig',[
            'user_article' => $article->getUserArticle($id)
        ]);
    }
    #[Route("legende/article/{id}", name:"article")]
    public function displayArticle($id, ArticleRepository $article){
        //dd( $article->find($id));
        return $this->render('legend/article.html.twig',[
            'legend' => $article->find($id)
        ]);
    }
}