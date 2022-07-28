<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/loginCheck", name="login_check")
     */
    public function loginCheck(AuthenticationUtils $authenticationUtils)
    {
                dd($authenticationUtils);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutCheck()
    {
        dd('logoutCheck');
    }

    /**
     * @Route("/create/user", name="user_create")
     */
    public function createAction(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasherConfig)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $password = $passwordHasherConfig->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles((array)($request->request->get('user')['roles']));

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }


}