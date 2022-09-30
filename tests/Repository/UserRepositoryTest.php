<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function createAndGetUser(): User
    {
        $em = $this->entityManager;
        $user = new User();
        $user->setEmail('test22@gmail.com');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword('passwordTest');
        $user->setUsername('username de test');

        $em->persist($user);
        $em->flush();

        return $em->getRepository(User::class)->findOneBy(['username' => 'username de test']);
    }

    public function testAddUser()
    {
        self::bootKernel();

        $user = $this->createAndGetUser();

        $this->assertEquals('test22@gmail.com', $user->getEmail());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertEquals("username de test", $user->getUsername());
    }

    public function testGetUser()
    {
        self::bootKernel();
        $em = $this->entityManager;
        $userDoesnotExist = $em->getRepository(User::class)->find(10000);
        $this->assertEquals(null, $userDoesnotExist);

        $user = $this->createAndGetUser();

        $this->assertEquals('test22@gmail.com', $user->getEmail());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertEquals('username de test', $user->getUsername());
    }

    public function testEditUser()
    {
        self::bootKernel();

        $user = $this->createAndGetUser();

        $this->assertEquals('test22@gmail.com', $user->getEmail());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertEquals('username de test', $user->getUsername());

        $user->setEmail("newtest22@gmail.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setUsername("newUsername");

        $em = $this->entityManager;

        $userId = $user->getId();
        $em->persist($user);
        $em->flush();

        $userToCheck = $em->getRepository(User::class)->find($userId);

        $this->assertEquals('newtest22@gmail.com', $userToCheck->getEmail());
        $this->assertContains('ROLE_USER', $userToCheck->getRoles());
        $this->assertEquals('newUsername', $userToCheck->getUsername());
    }

    //not working cause detached entity..
    public function testRemoveUser()
    {
        self::bootKernel();
        $em = $this->entityManager;

        $user = new User();

        $user->setUsername("userToDelete");
        $user->setPassword("test");
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setEmail("userToDelete@gmail.com");

        $em->persist($user);
        $em->flush();

        $userId = $user->getId();

        $em->remove($user);
        $em->flush();

        $userToCheck = $em->getRepository(User::class)->find($userId);

        $this->assertNull($userToCheck);
    }
}