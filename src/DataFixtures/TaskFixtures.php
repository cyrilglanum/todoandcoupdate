<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
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
        for ($i = 1; $i <= 100; $i++) {
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

        $user = new User();
        $user->setUsername("User2");
        $user->setEmail("utilisateur@glanum.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword('$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i');

        //creation des tâches utilisateur@glanum.com
        for ($i = 1; $i <= 5; $i++) {
            $task = new Task();
            $task->setContent("User2 va faire une tâche $i");
            $task->setTitle("Tâche User2");
            $task->setAuthor($user);
            $task->setIsDone(0);
            $task->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->persist($task);

            $manager->flush();
        }
    }
}
