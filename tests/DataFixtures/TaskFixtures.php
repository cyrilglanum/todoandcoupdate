<?php

namespace App\Tests\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    /**
     * @codeCoverageIgnore
     */
    public function load(ObjectManager $manager): void
    {
        //creation des tâches
        for ($i = 1; $i <= 200; $i++) {
            $task = new Task();
            $task->setContent("User$i va faire une tâche $i");
            $task->setTitle("Tâche $i");
            $task->setAuthor(null);
            $task->setIsDone(0);
            $task->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->persist($task);

            $manager->flush();
        }

        //creation des tâche anonymes
        for ($i = 1; $i <= 3; $i++) {
            $task = new Task();
            $task->setContent("Anonymous task content");
            $task->setTitle("Task anonymous");
            $task->setAuthor(null);
            $task->setIsDone(0);
            $task->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->persist($task);

            $manager->flush();
        }
    }
}
