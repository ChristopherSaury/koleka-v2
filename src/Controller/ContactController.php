<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController{
    #[Route("/contact", name:"contact")]
    public function contact(){
        return $this->render('contact/contact.html.twig',[
            'controller_name' => 'ContactController'
        ]);
    }
    #[Route('/contact/message/envoyer', name:'envoyer_msg')]
    public function sendMail(MailerInterface $mailer){
        if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email_id'])
        || empty($_POST['subject']) || empty($_POST['message'])){
            $this->addFlash('errorContact', 'Vous devez remplir tout les champs');
            return $this->redirectToRoute('contact');
        }else{

                $contact_message = (new Email())
                ->from($_POST['email_id'])
                ->to('legendkoleka@gmail.com')
                ->subject($_POST['subject'])
                ->text(
                    'Expéditeur : ' . $_POST['email_id'] . \PHP_EOL 
                    . 'Nom : ' . $_POST['lastname'] . \PHP_EOL
                    . 'Prénom : '. $_POST['firstname'] . \PHP_EOL
                    . 'Message : ' . \PHP_EOL . $_POST['message'],
                 'text/plain');

                $recaptcha = $_POST['g-recaptcha-response'];
                $secret_key = $_ENV['GOOGLE_RECAPTCHA_SECRET'];

                $url = 'https://www.google.com/recaptcha/api/siteverify?secret='. $secret_key . '&response=' . $recaptcha;
                $response = file_get_contents($url);
                $response = json_decode($response);

                if ($response->success == true) {
                    $mailer->send($contact_message);
                    $this->addFlash('successContact', 'Message envoyé');
                    return $this->redirectToRoute('contact');
                } else {
                    $this->addFlash('errorContact', 'Vous devez cocher la captcha');
                    return $this->redirectToRoute('contact');
                } 
                 
        }
        return $this->redirectToRoute('error_500');
        
    }
}