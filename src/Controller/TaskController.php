<?php

namespace App\Controller;


use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/task_create", name="task_create")
     */
    public function taskCreate(ManagerRegistry $doctrine)
    {
        dd('creation de tâches');
    }

    /**
     * @Route("/task_list", name="task_list")
     */
    public function taskList(ManagerRegistry $doctrine)
    {
                dd('Liste de tâches');

    }

    /**
     * @Route("/task_done", name="task_done")
     */
    public function taskDone(ManagerRegistry $doctrine)
    {
                dd('Liste de tâches terminées');

    }

}