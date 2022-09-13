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
     * @Route("/task_create", name="task_create")
     */
    public function taskCreate(Request $request)
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $task = new Task();
        $task->setIsDone(0);
        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setAuthor($this->getUser()->getId());

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $task = new Task();
            if ($task->getAuthor() === null) {
                $task->setAuthor($this->getUser()->getId());
            }
            $task->setTitle($request->request->get('task')['title']);
            $task->setContent($request->request->get('task')['content']);
            $task->setCreatedAt(new \DateTimeImmutable('now'));
            $task->setIsDone(false);
            
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list', ['user' => $this->getUser()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/task_list", name="task_list")
     */
    public function taskList()
    {
        $tasks = $this->doctrine->getRepository(Task::class)->findAll();

        return $this->render('task/list.html.twig', ['tasks' => $tasks, 'user' => $this->getUser()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function taskEdit(Request $request,Task $id)
    {

        $form = $this->createForm(TaskType::class, $id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id->setTitle($request->request->get('task')['title']);
            $id->setContent($request->request->get('task')['content']);
            $id->setCreatedAt(new \DateTimeImmutable('now'));

            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $id,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $id)
    {
        $id->toggle(!$id->isDone());

        $this->doctrine->getManager()->flush();

        if ($id->isDone() === true) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $id->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $id->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $id)
    {
        $em = $this->doctrine->getManager();
        $em->remove($id);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/done", name="task_done")
     */
    public function doneTaskAction(ManagerRegistry $doctrine)
    {
        $tasks = $doctrine->getManager()->getRepository(Task::class)->findBy(['isDone' => true]);
        $user = $this->getUser();

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'user' => $user,
        ]);
    }

}