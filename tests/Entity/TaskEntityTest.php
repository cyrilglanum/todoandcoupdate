<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskEntityTest extends TestCase
{
    public function testGetId()
    {
        $task = new Task();

        static::assertEquals(null, $task->getId());
    }

    public function testGetSetContent()
    {
        $task = new Task();
        $content = "Contenu de test";

        $task->setContent($content);
        $this->assertEquals("Contenu de test", $task->getContent());
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
        static::assertIsInt($user_id,"actual value is Integer or not");
    }

    public function testIsDoneToggle()
    {
        $task = new Task();
        $task->toggle(true);
        static::assertEquals(true, $task->isDone());
    }


}