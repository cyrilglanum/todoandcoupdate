<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(Request $request, ManagerRegistry $doctrine)
    {
        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('app_home');
        }

        if($user){
            $roles = $this->getUser()->getRoles();
        }

        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', ['users' =>
            $users]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editAction(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasherConfig, $id)
    {
        $user = $doctrine->getRepository(User::class)->find($id);

        if (!$user) {
            $users = $doctrine->getManager()->getRepository(User::class)->findAll();

            return $this->render('user/list.html.twig', ['users' => $users]);
        }
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $user->setRoles((array)($request->request->get('user_edit')['roles']));

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

}