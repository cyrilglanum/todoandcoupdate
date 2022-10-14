<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, UserRepository $userRepository)
    {
        $this->doctrine = $doctrine;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        $user = $this->getUser();

        if ($user === null) {
            return new Response("Vous n'êtes pas autorisé à voir cette page.", 403, ["Content-Type" => "application/json"]);
        }

        if ($user) {
            $roles = $this->getUser()->getRoles();

            $admin = false;
            foreach ($roles as $role) {
                if (str_contains('ROLE_ADMIN', $role)) {
                    $admin = true;
                }
            }

            if ($admin) {
                $users = $this->doctrine->getRepository(User::class)->findAll();

                return $this->render('user/list.html.twig', ['users' =>
                    $users]);
            }
        }

        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->doctrine->getRepository(User::class)->find($id);

        if (!$user) {
            $users = $this->doctrine->getManager()->getRepository(User::class)->findAll();

            return $this->render('user/list.html.twig', ['users' => $users]);
        }
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $user->setRoles((array)($request->request->get('user_edit')['roles']));

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteAction($id, ManagerRegistry $managerRegistry)
    {
        $em = $this->doctrine->getManager();
        $user = $this->doctrine->getRepository(User::class)->find($id);

        if (!$user) {
            $users = $em->getRepository(User::class)->findAll();

            return $this->render('user/list.html.twig', ['users' => $users]);
        }

        if($this->getUser() !== null ){
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $this->userRepository->remove($user, true);

                $this->addFlash('success', 'L\'utilisateur a bien été supprimée.');
            }
        }

        return $this->render('user/list.html.twig', ['users' => $em->getRepository(User::class)->findAll()]);
    }

}