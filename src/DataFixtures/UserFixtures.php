<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setUsername("User$i");
            $user->setEmail("email$i@test.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword(hash('md5',"aaaa"));
            $manager->persist($user);

            $manager->flush();
        }
    }
}
