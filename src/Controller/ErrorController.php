<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController{
    #[Route('/erreur/404', name:'error_404')]
    public function Error404(){
        return $this->render('error/error404.html.twig');
    }
    #[Route('/erreur/403', name:'error_403')]
    public function Error403(){
        return $this->render('error/error403.html.twig');
    }
    #[Route('/erreur/500', name:'error_500')]
    public function Error500(){
        return $this->render('error/error500.html.twig');
    }
}