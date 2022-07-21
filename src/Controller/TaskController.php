<?php

namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

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
    public function taskCreate(Request $request, ManagerRegistry $doctrine)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $task = new Task();

            if ($form->getData()->getAuthor() === null) {
                $task->setAuthor($this->getUser()->getId());
            }
            $task->setTitle($request->request->get('task')['title']);
            $task->setContent($request->request->get('task')['content']);
            $task->setCreatedAt(new \DateTimeImmutable('now'));
            $task->setIsDone(0);

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/task_list", name="task_list")
     */
    public function taskList(Request $request, ManagerRegistry $doctrine)
    {
        $tasks = $doctrine->getRepository(Task::class)->findAll();

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function taskEdit(Request $request, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Request $request, ManagerRegistry $doctrine, Task $id)
    {
        $em = $doctrine->getManager();

        $id->toggle(!$id->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        dd("taskToggle");

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Request $request, ManagerRegistry $doctrine, Task $id)
    {
        $em = $doctrine->getManager();
        $em->remove($id);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }

}