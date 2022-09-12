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
        $this->assertEquals(10, $tasks);
    }

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

//    public function testAddTaskWithNotGoodValuesInRepository()
//    {
//        self::bootKernel();
//
//        $task = new Task();
//        $task->setTitle('');
//        $task->setAuthor('');
//        $task->setCreatedAt('');
//        $task->setIsDone('');
//        $task->setContent('');
//
//        // Now, mock the repository so it returns the mock of the employee
//        $taskRepository = $this->createMock(ObjectRepository::class);
//    }

//    public function testValidEntity(ManagerRegistry $doctrine)
//    {
//        $task = $this->getEntity();
//        self::bootKernel();
//
//        $em = $doctrine->getManager();
//
//        dd($task);
//        $em->persist($task);
//        $em->flush();
//
//        $this->assertInstanceOf(Task::class, $task);
////        $this->assert
//    }







//     public function testCreateValidTitleTask()
//    {
//        $task = new Task();
//        $task->setTitle(1000);
//
//        $taskRepository = $this->createMock(TaskRepository::class);
//
////        $this->assertEquals(2100, $salaryCalculator->calculateTotalSalary(1));
//    }


}