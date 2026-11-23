<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AccountController extends AbstractController
{
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        $loginError = null;
        //dump($error);
        if($error instanceof TooManyLoginAttemptsAuthenticationException){
            // l'erreur est due à la limitation de la connexion
            $loginError = "Trop de tentative de connexion! Ressayez plus tard.";
        }

        return $this->render('account/index.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
            'loginError' => $loginError
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @return void
     */
    #[Route('/logout', name: 'account_logout')]
    public function logout(): void
    {
    }

    /**
     * Permet d'inscrire un utilisateur
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route("/register", name: "account_register")]
    public function register(Request $request,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // exemple de code : Kx9#mQ2$vL8!pZ5@
            $hash = $hasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hash);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render("account/registration.html.twig",[
            "myForm" => $form->createView()
        ]);
    }

    #[Route("/account/profile", name:"account_profile")]
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser(); // récup l'utilisateur connecté
        $form = $this->createForm(AccountType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                "success",
                "Les données ont été enregistrées avec succès"
            );
        }


        return $this->render("account/profile.html.twig",[
            "myForm" => $form->createView()
        ]);

    }

    #[Route("/account/password-update", name:"account_password")]
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $passwordUpdate = new PasswordUpdate();
        // exemple de code : Gx9#mQ3$vL5!p27@
        // besoin du User
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getPassword())){
                // gérer l'erreur
                $form->get("oldPassword")->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user,$newPassword);
                $user->setPassword($newPassword);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    "success",
                    "Votre mot de passe a bien été modifié"
                );
                return $this->redirectToRoute("homepage");
            }
        }

        return $this->render("account/password.html.twig",[
            "myForm" => $form->createView()
        ]);
    }


}
