<?php

namespace App\Controller;

use App\Form\EditNewsletterType;
use App\Entity\NewsletterDocument;
use Symfony\Component\Mime\Address;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsletterDocumentRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class NewsletterController extends AbstractController{
    #[Route('/admin/newsletter', name:'newsletter_list')]
    #[IsGranted('ROLE_USER')]
    public function newsletterList(NewsletterDocumentRepository $nslRepo){

        return $this->render('form/nsl-list.html.twig',[
            'newsletters' => $nslRepo->findAll()
        ]);
    }
   
    #[Route('/admin/newsletter/preparation', name:'newsletter_prepare')]
    public function nslPrepare(Request $request, EntityManagerInterface $em, SluggerInterface $slugger){
        $new_nsl = new NewsletterDocument;
        $new_nsl->setIsSent(false); 
        $form = $this->createForm(EditNewsletterType::class, $new_nsl);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $nsl_attachment = $form->get('attachment')->getData();

            if($nsl_attachment){
                $originFileName = pathinfo($nsl_attachment->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originFileName);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $nsl_attachment->guessExtension();

                try{
                    $nsl_attachment->move(
                        $this->getParameter('koleka_newsletter'),
                        $newFilename
                    );
                } catch (FileException){
                    $this->redirectToRoute('error_500');
                }
                $new_nsl->setAttachment($newFilename);
            }
            $em->persist($new_nsl);
            $em->flush();
            return $this->redirectToRoute('newsletter_list');
        }

        return $this->render('form/nsl-prepare.html.twig',[
            'nslCreate' => $form->createView()
        ]);
    }

    #[Route('/admin/newsletter/modifier/{id}', name:'newsletter_update')]
    public function newsletterUpdate(Request $request, NewsletterDocument $nsl_doc, SluggerInterface $slugger, EntityManagerInterface $em){
        $form = $this->createForm(EditNewsletterType::class, $nsl_doc);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $nsl_attachment = $form->get('attachment')->getData();

            if($nsl_attachment){
                $originFileName = pathinfo($nsl_attachment->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originFileName);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $nsl_attachment->guessExtension();

                try{
                    $nsl_attachment->move(
                        $this->getParameter('koleka_newsletter'),
                        $newFilename
                    );
                } catch (FileException $e){
                    return $this->redirectToRoute('error_500');
                }
                $previous_doc = basename($nsl_doc->getAttachment());
                unlink($this->getParameter('kernel.project_dir') . '/public/newsletter-document/' . $previous_doc);

                $nsl_doc->setAttachment($newFilename);
            }

            $em->persist($nsl_doc);
            $em->flush();

            return $this->redirectToRoute('newsletter_list');
    }

    return $this->render('form/nsl-update.html.twig',[
        'updateForm' => $form->createView()
    ]);
}

    #[Route('/admin/newsletter/envoyer/{id}', name:'newsletter_send')]
    public function newsletterSend($id, NewsletterDocumentRepository $nslRepo, NewsletterRepository $sub, MailerInterface $mailer, EntityManagerInterface $em){
        $nsl_to_send = $nslRepo->find($id);
        $subscribers = $sub->findAll();
        $unsublink = 'https://asso-koleka.fr/abonnement/supprimer/';

        foreach ($subscribers as $subscriber){
            if($subscriber){
                $email = (new TemplatedEmail())
                ->from(new Address('legendkoleka@gmail.com', 'Team Koleka'))
                ->to($subscriber->getSubscriptionEmail())
                ->subject('Newsletter Koleka : ' . $nsl_to_send->getDate()->format('m-Y'))
                ->htmlTemplate('form/nsl-doc.html.twig')
                ->context(compact('nsl_to_send', 'subscriber', 'unsublink'));

                if($nsl_to_send->getAttachment()){
                   $email->attachFromPath($this->getParameter('koleka_newsletter') . '/' . $nsl_to_send->getAttachment());
                }
                $mailer->send($email);

                $nsl_to_send->setIsSent(true);
                $em->persist($nsl_to_send);
                $em->flush();
            }
        }
        return $this->redirectToRoute('newsletter_list');
    }

    #[Route('/newsletter/supprimer/{id}', name:'newsletter_delete')]
    public function deleteNsl(NewsletterDocument $nsl, EntityManagerInterface $em){
        $em->remove($nsl);
        $em->flush();

        return $this->redirectToRoute('newsletter_list');
    }
}