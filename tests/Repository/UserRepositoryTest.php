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


    public function createAndGetUser():User
    {
        $em = $this->entityManager;
        $user = new User();
        $user->setEmail('test22@gmail.com');
        $user->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $user->setPassword('passwordTest');
        $user->setUsername('username de test');

        $em->persist($user);
        $em->flush();

        $userWithUsernameJustCreated = $em->getRepository(User::class)->findOneBy(['username' => 'username de test']);

        return $userWithUsernameJustCreated ;
    }

    public function testAddUser()
    {
        self::bootKernel();

        $user = $this->createAndGetUser();

        $this->assertEquals('test22@gmail.com', $user->getEmail());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertEquals("username de test", $user->getUsername());
    }

    public function testEditUser()
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
}