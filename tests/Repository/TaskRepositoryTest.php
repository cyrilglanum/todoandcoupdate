<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount()
    {
       self::bootKernel();
       $users = self::$container->get(TaskRepository::class)->count([]);
       $this->assertEquals(10,$users);
    }

}