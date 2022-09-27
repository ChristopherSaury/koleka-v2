<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController{
    #[Route("/a-propos", name:"a-propos")]
    public function about(){
        return $this->render('about/about.html.twig',[
            'controller_name' => 'AboutController'
        ]);
    }
}