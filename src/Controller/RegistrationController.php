<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\UpdateUserFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    #[Route('/inscription', name:'app_register')]
    public function register(): Response
    {
       
        return $this->render('registration/signup.html.twig',[
            'controller_name' => 'RegistrationController'
        ]);
    }

    #[Route('/inscription/handler', name:'register_handler')]
    public function register_handler(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager){
        if(empty($_GET['firstname']) || empty($_GET['lastname']) || empty($_GET['email'])
        || empty($_GET['plainPassword']) || empty($_GET['cf-psw'])){
            $this->addFlash('error', 'Vous devez remplir tous les champs du formulaire');
            return $this->redirectToRoute('app_register');
        }elseif(!preg_match('/^[a-zA-Z]+$/',$_GET['firstname']) || !preg_match('/^[a-zA-Z]+$/',$_GET['lastname'])){
            $this->addFlash('error', 'les champs nom et prénom doivent contenir des lettres uniquement');
            return $this->redirectToRoute('app_register');
        }elseif($_GET['plainPassword'] !== $_GET['cf-psw']){
            $this->addFlash('error', 'Le mot de passe et sa confirmation doivent être identiques');
            return $this->redirectToRoute('app_register');
        }elseif(strlen($_GET['plainPassword']) < 7 || strlen($_GET['plainPassword']) > 30 ){
            $this->addFlash('error', 'Le mot de passe doit contenir entre 8 et 30 caractères');
            return $this->redirectToRoute('app_register');
        }elseif(!isset($_GET['agreeTerms'])){
            $this->addFlash('error', 'Vous devez accepter les termes et conditions d\'utilisation');
            return $this->redirectToRoute('app_register');
        }else{
            
            $user = new User;
            $user->setFirstname($_GET['firstname']);
            $user->setLastname($_GET['lastname']);
            $user->setEmail($_GET['email']);
            $user->setTermOfUse($_GET['agreeTerms']);
            $user->setCreationDate(new DateTime());
            $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $_GET['plainPassword']
                            )
                        );
            $entityManager->persist($user);
            $entityManager->flush();
        
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('legendkoleka@gmail.com', 'Team Koleka'))
                    ->to($user->getEmail())
                    ->subject('Confirmer votre compte')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        return $this->redirectToRoute('accueil');
    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('accueil');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('accueil');
    }

    #[Route('/utilisateur/modifier/informations/{id}', name:'update_user')]
    #[IsGranted('ROLE_USER')]
    public function updateUser($id ,Request $request, User $user, EntityManagerInterface $em){
        if($this->getUser()->getId() != $id){
            return $this->redirectToRoute('error_403');
        } 
            $form = $this->createForm(UpdateUserFormType::class, $user);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                
                $em->persist($user);
                $em->flush();
                $this->addFlash('updUserSuccess', 'Données modifiées avec succès');
                return $this->redirect($this->generateUrl('update_user',[ 'id' => $this->getUser()->getId()]));
                
            }
    
            return $this->render('parameters/updateUser.html.twig',[
                'updateUserForm' => $form->createView()
            ]);
    }

    #[Route('/utilisateur/modifier/identifiant/{id}', name:'update_psw')]
    #[IsGranted('ROLE_USER')]
    public function updatePsw($id ,Request $request, User $user,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em){
        if($this->getUser()->getId() != $id){
            return $this->redirectToRoute('error_403');
        }
        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $new_password = $form->get('plainPassword')->getData();
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $new_password
                    ));
            $em->persist($user);
            $em->flush();

            $this->addFlash('updPswSuccess', 'Mot de passe modifié avec succès');
            return $this->redirect($this->generateUrl('update_psw',['id' => $this->getUser()->getId()]));
        }

        return $this->render('parameters/updatePsw.html.twig', [
            'updatePswForm' => $form->createView()
        ]);
    }
}
