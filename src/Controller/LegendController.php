<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LegendController extends AbstractController{
    #[Route("/legende", name:"legende")]
    public function legende(){
        return $this->render('legend/legend.html.twig',[
            'controller_name' => 'LegendController'
        ]);
    }
}