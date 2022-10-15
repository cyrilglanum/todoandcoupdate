<?php

namespace App\Tests\Repository;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function createAndGetTask(): Task
    {
        $em = $this->entityManager;

        $user = new User();
        $user->setUsername("Userx");
        $user->setEmail("utilisateurx@glanum.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword('$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i');

        $task = new Task();
        $task->setAuthor($user);
        $task->setTitle('test title');
        $task->setContent('content');
        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setIsDone(0);

        $em->persist($task);
        $em->flush();

        return $em->getRepository(Task::class)->findOneBy(['createdAt' => $task->getCreatedAt(), 'user' => $user]);
    }

    public function testAddTask()
    {
        $taskWithTitleJustCreated = $this->createAndGetTask();

        $this->assertEquals('test title', $taskWithTitleJustCreated->getTitle());
        $this->assertEquals('content', $taskWithTitleJustCreated->getContent());
    }



    public function testEditTask()
    {

        $user = new User();
        $user->setUsername("Userxy");
        $user->setEmail("utilisateurxy@glanum.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword('$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i');

        self::bootKernel();
        $em = $this->entityManager;
        $taskDoesnotExist = $em->getRepository(Task::class)->find(506);
        $this->assertEquals(null, $taskDoesnotExist);
        $task = $em->getRepository(Task::class)->find(1);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(null, $task->getAuthor());

        $task->setAuthor($user);
        $task->setTitle('test new title from task with author 4');
        $task->setContent('New author id 6');
        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setIsDone(1);

        $em->persist($task);
        $em->flush();

        $taskWithTitleJustCreated = $em->getRepository(Task::class)->findOneBy(['title' => 'test new title from task with author 4']);

        $this->assertEquals('test new title from task with author 4', $taskWithTitleJustCreated->getTitle());
        $this->assertEquals('New author id 6', $taskWithTitleJustCreated->getContent());
    }

    public function testRemoveTask()
    {
        self::bootKernel();
        $em = $this->entityManager;

        $task = new Task();

        $task->setTitle("test");
        $task->setContent("test");
        $task->setAuthor(null);
        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setIsDone(0);

        $em->persist($task);
        $em->flush();

        $taskId = $task->getId();

        $em->remove($task);
        $em->flush();

        $taskToCheck = $em->getRepository(Task::class)->find($taskId);

        $this->assertNull($taskToCheck);
    }

}