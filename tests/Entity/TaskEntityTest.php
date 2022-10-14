<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskEntityTest extends KernelTestCase
{

    public function getEntity():Task
    {
        $task = new Task();

        $task->setContent('$content');
        $task->setAuthor(1);
        $task->setTitle("1 titre parmi d autres");
        $task->setIsDone(0);
        $task->setCreatedAt(new \DateTimeImmutable());

        return $task;
    }

    public function testValidEntity()
    {
        $task = $this->getEntity();
        self::bootKernel();

        $error = self::$container->get('validator')->validate($task);
        $this->assertCount(0, $error);
    }

//    public function testInvalidEntity()
//    {
//        $task = $this->getEntity()->setAuthor('testInvalid');
//        self::bootKernel();
//
//        $error = self::$container->get('validator')->validate($task);
//        $this->assertCount(1, $error);
//    }

//    public function testInvalidBlankAuthorEntity()
//    {
//        $task = $this->getEntity()->setAuthor('');
//        self::bootKernel();
//
//        $error = self::$container->get('validator')->validate($task);
//        $this->assertCount(1, $error);
//    }

    public function testInvalidBlankTitleEntity()
    {
        $task = $this->getEntity()->setTitle('');
        self::bootKernel();

        $error = self::$container->get('validator')->validate($task);
        $this->assertCount(1, $error);
    }

    public function testGetId()
    {
        $task = new Task();

        static::assertEquals(null, $task->getId());
        static::assertInstanceOf(Task::class, $task);
    }

    public function testGetSetContent()
    {
        $task = new Task();
        $content = "Contenu de test";

        $task->setContent($content);
        $this->assertEquals("Contenu de test", $task->getContent());
        $this->assertInstanceOf(Task::class, $task);
    }

    public function testGetSetTitle(): void
    {
        $task = new Task();
        $task->setTitle('Titre de test');
        static::assertEquals('Titre de test', $task->getTitle());
    }

    public function testGetSetCreatedAt(): void
    {
        $task = new Task();
        $task->setCreatedAt(new \DateTimeImmutable());
        static::assertInstanceOf(\DateTimeImmutable::class, $task->getCreatedAt());
    }

    public function testGetSetUser(): void
    {
        $task = new Task();
        $user_id = 6;
        $task->setAuthor($user_id);
        static::assertIsInt($user_id, "actual value is Integer or not");
        static::assertEquals($user_id, $task->getAuthor());

    }

    public function testIsDoneToggle()
    {
        $task = new Task();
        $task->toggle(true);
        static::assertEquals(true, $task->isDone());
    }

    public function testGetUserFromTask(): void
    {
        $task = new Task();
        $user_id = 7;

        $task->setAuthor($user_id);

        self::assertEquals($user_id, $task->getAuthor());
    }

}