<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StaticPageController extends AbstractController{
    #[Route("/", name:"accueil")]
    public function home(){
        return $this->render('static/home.html.twig',[
            'controller_name' => 'StaticPageController'
        ]);
    }
}