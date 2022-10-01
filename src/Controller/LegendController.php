<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function displayCountryPage($id, CountryRepository $country){
        $one_country = $country->find($id);
        return $this->render('legend/country.html.twig',[
            'controller_name'=> 'LegendController',
            'country' => $one_country
        ]);
    }
}