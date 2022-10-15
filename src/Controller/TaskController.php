<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    private $doctrine;
    private $taskRepository;

    public function __construct(ManagerRegistry $doctrine, TaskRepository $taskRepository)
    {
        $this->doctrine = $doctrine;
        $this->taskRepository = $taskRepository;
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
        $task->setAuthor($this->getUser());

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = new Task();

            $task->setAuthor($this->getUser());
            $task->setTitle($request->request->get('task')['title']);
            $task->setContent($request->request->get('task')['content']);
            $task->setCreatedAt(new \DateTimeImmutable('now'));
            $task->setIsDone(false);

            $this->taskRepository->add($task, true);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list', ['user' => $this->getUser()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/task_list", name="task_list")
     */
    public function taskList(PaginatorInterface $pager, Request $request)
    {
        $tasks = $this->doctrine->getRepository(Task::class)->findBy(['isDone' => '0'], ['id' => 'DESC']);

        $tasksPaginator = $pager->paginate(
            $tasks,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('task/list.html.twig', ['tasks' => $tasksPaginator]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function taskEdit(Request $request, Task $id)
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
    public function toggleTaskAction(Task $id, ManagerRegistry $doctrine)
    {
        $id->toggle(!$id->isDone());

        $this->doctrine->getManager()->flush();

        if ($id->isDone() === false) {
            $this->addFlash('success', sprintf('La tâche %s a bien été comme non terminée.', $id->getTitle()));

            $tasks = $doctrine->getManager()->getRepository(Task::class)->findBy(['isDone' => true]);
            $user = $this->getUser();

            return $this->render('task/list.html.twig', [
                'tasks' => $tasks,
                'user' => $user,
            ]);
        }

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée marquée comme faite.', $id->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $id)
    {
        if ($id->getAuthor()->getId() === $this->getUser()->getId() || in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $this->taskRepository->remove($id, true);

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer la tâche.');
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/done", name="task_done")
     */
    public function doneTaskAction(ManagerRegistry $doctrine, PaginatorInterface $pager, Request $request)
    {
        $tasks = $doctrine->getManager()->getRepository(Task::class)->findBy(['isDone' => true], ['id' => 'DESC']);
        $user = $this->getUser();

        $tasksPaginator = $pager->paginate(
            $tasks,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('task/list.html.twig', [
            'tasks' => $tasksPaginator,
            'user' => $user,
        ]);
    }

}