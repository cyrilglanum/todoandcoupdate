<?php

namespace App\Tests\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @codeCoverageIgnore
     */
    public function load(ObjectManager $manager): void
    {
        dd("iic");

//        for ($i = 1; $i <= 1; $i++) {
//            $user = new User();
//            $user->setUsername("UserAdmin");
//            $user->setEmail("cyril@glanum.com");
//            $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
////            password : "aaaa"
//            $user->setPassword('$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i');
//            $manager->persist($user);
//
//            $manager->flush();
//        }

        for ($i = 1; $i <= 1; $i++) {
            $user = new User();
            $user->setUsername("Utilisateur");
            $user->setEmail("utilisateur@glanum.com");
            $user->setRoles(["ROLE_USER"]);
//            password : "aaaa"
            $user->setPassword('$2y$13$tcRfFUVtQCqGV5rcyTpB7OGjcDcmdNQArBNbkGkTtMuABo79I.g2i');
            $manager->persist($user);

            $manager->flush();
        }

        for ($i = 1; $i <= 500; $i++) {
            $user = new User();
            $user->setUsername("User$i");
            $user->setEmail("email$i@test.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword(hash('md5', "aaaa"));
            $manager->persist($user);

            $manager->flush();
        }
    }
}
