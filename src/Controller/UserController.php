<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function listAction(ManagerRegistry $doctrine)
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        dd($users);

        return $this->render('user/list.html.twig', ['users' =>
        $users]);
    }

}