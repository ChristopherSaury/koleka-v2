<?php

namespace App\Controller;

use DateTime;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NslSubController extends AbstractController{
    #[Route('/abonnement/newsletter', name:'sub')]
    public function newsletterPage(Request $request, EntityManagerInterface $em, MailerInterface $mailer){
        $new_sub = new Newsletter;
        $new_sub->setDate(new DateTime());
        $form = $this->createForm(NewsletterType::class, $new_sub);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($new_sub);
            $em->flush();

            $email = (new TemplatedEmail())
            ->from(new Address('legendkoleka@gmail.com', 'Team Koleka'))
            ->to($new_sub->getSubscriptionEmail())
            ->subject('Confirmation souscription newsletter')
            ->htmlTemplate('form/nsl-confirm.html.twig');
            $mailer->send($email);

            $this->addFlash('success', 'Demande d\'abonnement envoyée');
            return $this->redirectToRoute('sub');
        }
        return $this->render('form/newsletter-sub.html.twig',[
            'nslForm' => $form->createView()
        ]);
    }
    #[Route('/abonnement/supprimer/{id}', name:'sub_cancel')]
    public function nslUnsubscription(Newsletter $sub, EntityManagerInterface $em){
        $em->remove($sub);
        $em->flush();

        return new Response('desinscription avec succès', 200);
    }
}