<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function index(AuthenticationUtils $utils)
    {
        $error= $utils->getLastAuthenticationError();
        $username= $utils->getLastUsername();
        dump($error);

        return $this->render('account/login.html.twig',[
            'hasError'=>$error !== null,
            'username'=>$username

        ]);
    }

    /**
     * @Route("/register",name="account_register")
     */

    public function register(Request $request, UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager){

        $user = new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user,$user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"
            votre compte a bien créé
            ");
            return  $this->redirectToRoute('account_login');

        }



        return $this->render('account/registration.html.twig',[
            'form'=>$form->createView()

        ]);


    }


    /**
     * @Route("/account/profile",name="account_profil")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function Profile(Request $request,EntityManagerInterface $manager){
        $user =$this->getUser();
        $form=$this->createForm(AccountType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"
            Les donnnés du profil modifier avec succeess  
            ");
        }
            return $this->render('account/profil.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/account/password-update",name="account_password")
     */
    public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager){

        $user =$this->getUser();
        $passwordUpdate = new PasswordUpdate();
        $form=$this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        dump($user->getHash());
        if ($form->isSubmitted() && $form->isValid()){
            // 1 verfier que le oldpassword  soir le mem que le paassword utlisateur
           if(!password_verify($passwordUpdate->getOldPassword(),$user->getHash())){
            $form->get('oldPassword')->addError(new FormError("le mot de passe que vous avez tapé n'est pas votre mot  de passe actuel")) ;
           }
           else{
               $newPassword = $passwordUpdate->getNewPassword();
               $hash = $encoder->encodePassword($user,$newPassword);

               $user->setHash($hash);
               $manager->persist($user);
               $manager->flush();
               $this->addFlash(
                   'success',
                   "Votre mot de passe a bien été modifié"
               );

           }

            return $this->redirectToRoute('home');

        }

        return $this->render('account/password.html.twig',[
            'test'=>$form->createView()

        ]

        );

    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){

    }

    /**
     *  @Route("/account", name="account_index")
     */
    public function myAccount(){

    return $this->render('user/index.html.twig',
    [
        'user'=>$this->getUser()

    ])

        ;

    }
}
