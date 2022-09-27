<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\User;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController{
    #[Route("/inscription", name:"inscription")]
    public function signup(){
        return $this->render('signup/signup.html.twig',[
            'controller_name' => 'SignupController'
        ]);
    }
    #[Route("/inscription/handler", name:"inscription_handler")]
    public function signup_handler(EntityManagerInterface $em, StatusRepository $status, Request $request){
        if(!empty($_GET['lastname']) && !empty($_GET['firstname'])
        && !empty($_GET['user_email']) && !empty($_GET['password'])
        && !empty($_GET['cf-psw']) && isset($_GET['term_of_use'])){

            $_GET['password'] = password_hash($_GET['password'], PASSWORD_BCRYPT);

            $new_user = new User;
            $new_user->setLastname($_GET['lastname']);
            $new_user->setFirstname($_GET['firstname']);
            $new_user->setUserEmail($_GET['user_email']);
            $new_user->setPassword($_GET['password']);
            $new_user->setTermsOfUse($_GET['password']);
            $new_user->setStatus($status->find($id = 1));
            

            $em->persist($new_user);
            $em->flush();

            $session = $request->getSession();
            $session->set('username', $new_user->getUsername());

            
            // return $this->redirectToRoute('accueil');
        //dd("ok");
        // $user->insertUser($_GET['lastname'], $_GET['firstname'], $_GET['user_email'], $_GET['password'], $_GET['term_of_use']);
        return $this->redirectToRoute('accueil');
        }else{
            dd("pas ok");
            //dd(array($_GET['lastname'], $_GET['firstname'], $_GET['user_email'], $_GET['password'], $_GET['cf-psw'], $_GET['term_of_use']));
        }
    }
}