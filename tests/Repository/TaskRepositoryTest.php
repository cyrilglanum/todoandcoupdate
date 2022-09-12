<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount()
    {
       self::bootKernel();
       $tasks = self::$container->get(TaskRepository::class)->count([]);
       $this->assertEquals(10,$tasks);
    }

     public function testCreateValidTitleTask()
    {
        $task = new Task();
        $task->setTitle(1000);

        $taskRepository = $this->createMock(TaskRepository::class);

//        $this->assertEquals(2100, $salaryCalculator->calculateTotalSalary(1));
    }

}