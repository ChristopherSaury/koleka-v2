<?php

namespace App\Controller;

use App\Entity\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticPageController extends AbstractController{
    #[Route("/", name:"accueil")]
    public function home(){
        return $this->render('static/home.html.twig',[
            'controller_name' => 'StaticPageController'
        ]);
    }
    #[Route("/newsletter/handler", name:"add_newsletter")]
    public function newsletter(EntityManagerInterface $em){
        if(!empty($_POST['nsl-subscription']) && !empty($_POST['nsl-agreement-box'])){
            $new_sub = new Newsletter;
            $new_sub->setSubscriptionEmail($_POST['nsl-subscription']);
            $new_sub->setTermOfUse($_POST['nsl-agreement-box']);
            $em->persist($new_sub);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }else{
            return $this->redirectToRoute('accueil');
        }
    }
}