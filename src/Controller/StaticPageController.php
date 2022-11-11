<?php

namespace App\Controller;

use App\Entity\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticPageController extends AbstractController{
    #[Route("/", name:"accueil")]
    public function home(){
        if($this->getUser() == true && $this->getUser()->isVerified() == false){
            return $this->redirectToRoute('identication_required');
        }
        return $this->render('static/home.html.twig',[
            'controller_name' => 'StaticPageController'
        ]);
    }

    #[Route('/crédits', name:'credits')]
    public function credits(){
        return $this->render('static/credits.html.twig');
    }

    #[Route('/identification', name:'identication_required')]
    #[IsGranted('ROLE_USER')]
    public function identication(){
        return $this->render('static/identication.html.twig');
    }

    #[Route('/desabonnement/reussi', name:'unsub_success')]
    public function unsubSuccess(){
        return $this->render('form/nsl-unsubscribe.html.twig');
    }
}