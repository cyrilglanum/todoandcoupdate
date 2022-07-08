<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/loginCheck", name="login_check")
     */
    public function loginCheck()
    {
        dd('loginCheck');
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
    public function createUser(): Response
    {
        dd('creation user');
    }


}