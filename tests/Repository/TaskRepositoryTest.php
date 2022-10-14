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
        $task = new Task();
        $task->setAuthor(4);
        $task->setTitle('test title');
        $task->setContent('content');
        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setIsDone(0);

        $em->persist($task);
        $em->flush();

        return $em->getRepository(Task::class)->findOneBy(['createdAt' => $task->getCreatedAt()]);
    }

    public function testAddTask()
    {
        self::bootKernel();

        $taskWithTitleJustCreated = $this->createAndGetTask();

        $this->assertEquals('test title', $taskWithTitleJustCreated->getTitle());
        $this->assertEquals('content', $taskWithTitleJustCreated->getContent());
        $this->assertEquals(4, $taskWithTitleJustCreated->getAuthor());
    }

    public function testEditTask()
    {
        self::bootKernel();
        $em = $this->entityManager;
        $taskDoesnotExist = $em->getRepository(Task::class)->find(6);
        $this->assertEquals(null, $taskDoesnotExist);
        $task = $em->getRepository(Task::class)->find(3545);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(4, $task->getAuthor());

        $task->setAuthor(6);
        $task->setTitle('test new title from task with author 4');
        $task->setContent('New author id 6');
        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setIsDone(1);

        $em->persist($task);
        $em->flush();

        $taskWithTitleJustCreated = $em->getRepository(Task::class)->findOneBy(['title' => 'test new title from task with author 4']);

        $this->assertEquals('test new title from task with author 4', $taskWithTitleJustCreated->getTitle());
        $this->assertEquals('New author id 6', $taskWithTitleJustCreated->getContent());
        $this->assertEquals(6, $taskWithTitleJustCreated->getAuthor());
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