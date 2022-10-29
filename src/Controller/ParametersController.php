<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParametersController extends AbstractController{
    #[Route('/parametres', name:'parameters')]
    #[IsGranted('ROLE_USER')]
    public function parametersManagement () {
        return $this->render('parameters/parameters.html.twig');
    }
    #[Route('/compte/gestion', name:'account_management')]
    #[IsGranted('ROLE_USER')]
    public function accuount_management(){
        return $this->render('parameters/account.html.twig');
    }
    #[Route('/compte/supprimer/{id}', name:'delete_acount')]
    #[IsGranted('ROLE_USER')]
    public function deleteAccount($id, UserRepository $userRepo, Request $request){
        if($this->getUser()->getId() == $id){
            $userRepo->remove($this->getUser(), true);
            $session = new Session();
            $session->invalidate();
            
            return $this->redirectToRoute('app_logout');
        }else{
            return $this->redirectToRoute('error_403');
        }

        return $this->redirectToRoute('error_500');
       
    }

}