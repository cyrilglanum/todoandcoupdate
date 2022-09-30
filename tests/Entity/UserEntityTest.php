<?php

namespace App\Tests\Entity;

use App\DataFixtures\UserFixtures;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testValidEntity()
    {
        $task = new User();

        $task->setEmail('test@glanum.com');
        $task->setUsername('flaski');
        $task->setRoles(["ROLE_USER"]);
        $task->setPassword('password');
        self::bootKernel();

        $error = self::$container->get('validator')->validate($task);
        $this->assertCount(0, $error);
    }

    public function testGetSetUser()
    {
        $task = new User();
        $content = "cyrilg@gmail.com";

        $task->setEmail($content);
        $this->assertEquals("cyrilg@gmail.com", $task->getEmail());
    }

    public function testSetEmail(): void
    {
        $value = 'cyril@glanumtest.com';

        $user = New User();
        $user->setEmail($value);

        self::assertEquals($value, $user->getEmail());
    }

    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];

        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());

    }

    public function testSetRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        static::assertEquals($user->getRoles(), ['ROLE_ADMIN', 'ROLE_USER']);
    }

    public function testGetPassword(): void
    {
        $value = 'password';

        $response = $this->user->setPassword($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getPassword());

    }

}