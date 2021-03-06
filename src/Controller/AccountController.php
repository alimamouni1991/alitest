<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\Booking;

use App\Entity\PasswordUpdate;

use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    
    /**
     * permet d'afficher et gerer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function lougout(){

    }

    /**
     * permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé! Vous pouvez maintenent vous connecter !"
            );
            return $this->redirectToRoute('account_login');
         }

        return $this->render('account/registration.html.twig',[
            'form' =>$form->createView()
        ]);

    }


    /**
     * permet d'afficher et de traiter le formulaire de modifcation de profil
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request , EntityManagerInterface $entityManager){
        $user= $this->getUser();

        $form= $this->createForm(AccountType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont été enregistrée avec succès !"
            );


        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()

        ]);

    }
    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder){
        $passwordUpdate = new PasswordUpdate();
       
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted()  &&  $form->isValid()){

        if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){

            $form->get('oldPassword')->addError(new FormError("le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));

        }else{

            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->encodePassword($user, $newPassword);

            $user->setHash($hash);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre mot de passe a bien été modifier !"
            );

            return $this->redirectToRoute('homepage');

        }
    }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);

    }
    /**
     * Permet d'afficher la liste des réservations faites par l'utilisateur
     * 
     * @Route("/account/bookings", name="account_bookings")
     * 
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }

}
