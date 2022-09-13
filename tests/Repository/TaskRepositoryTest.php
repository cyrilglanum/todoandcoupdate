<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function getEntity()
    {
        $task = new Task();

        $task->setContent('TEST AJOUT ENTITY TASK');
        $task->setAuthor(1);
        $task->setTitle("test ajout");
        $task->setIsDone(0);
        $task->setCreatedAt(new \DateTimeImmutable());

        return $task;
    }

    public function testCount()
    {
        self::bootKernel();
        $tasks = self::$container->get(TaskRepository::class)->count([]);
        $this->assertEquals(11, $tasks);
    }

    //with fixture tests
    public function testGetGoodTasksWithAuthorIdValues()
    {
        self::bootKernel();

        $tasks = self::$container->get(TaskRepository::class)->findAll();

        foreach ($tasks as $task) {
            $this->assertMatchesRegularExpression('/' . $task->getAuthor() . '/', $task->getContent());
            $this->assertMatchesRegularExpression('/' . $task->getAuthor() . '/', $task->getTitle());
            $this->assertMatchesRegularExpression('/@/', $task->getTitle());
            $this->assertInstanceOf(\DateTimeImmutable::class, $task->getCreatedAt());
            $this->assertInstanceOf(Task::class, $task);
        }
    }

    public function testValidEntity()
    {
        $task = new Task();

        $task->setCreatedAt(new \DateTimeImmutable('now'));
        $task->setIsDone(0);
        $task->setContent('Test de content tâche');
        $task->setTitle('Test de title tâche');
        $task->setAuthor(21);

        self::bootKernel();

        $error = self::$container->get('validator')->validate($task);
        $this->assertCount(0, $error);
    }

    public function testAuthorTask()
    {
        $task = new Task();

        $task->setAuthor(21);
        $this->assertEquals(21, $task->getAuthor());
    }

    public function testCreatedAtTask(): void
    {
        $value = new \DateTimeImmutable('now');

        $task = new Task();
        $task->setCreatedAt($value);

        self::assertEquals($value, $task->getCreatedAt());
    }

    public function testSetTitleTask(): void
    {
        $task = new Task();
        $task->setTitle('Title tâche');

        self::assertEquals('Title tâche', $task->getTitle());
    }

    public function testSetContentTask(): void
    {
        $task = new Task();
        $task->setContent('Content tâche');

        self::assertEquals('Content tâche', $task->getContent());
    }



}