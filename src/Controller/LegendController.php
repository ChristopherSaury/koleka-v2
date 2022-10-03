<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CountryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    #[Route("/legende/pays/{id}", name:"one_country")]
    public function displayCountryPage($id, CountryRepository $country, ArticleRepository $article){
        $one_country = $country->find($id);
        //$article->getCountryArticle($id);
        dd($article->getCountryArticle($id));
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
        $date = new DateTime();
        $new_article->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $new_article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($new_article);
            $em->flush();
             
            return $this->redirectToRoute('accueil');
        }
        return $this->render('legend/create.html.twig',[
            'controller_name' => 'LegendController',
            'legendForm' => $form->createView(),
        ]);
    }
}