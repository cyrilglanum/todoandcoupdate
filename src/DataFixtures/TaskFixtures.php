<?php

namespace App\DataFixtures;

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
        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setContent("User$i va faire une tâche $i");
            $task->setTitle("email$i@test.com");
            $task->setAuthor($i);
            $task->setIsDone(0);
            $task->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->persist($task);

            $manager->flush();
        }
    }
}
