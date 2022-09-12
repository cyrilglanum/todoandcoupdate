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
    private const NOT_BLANK_MESSAGE = "Veuillez choisir une valeur.";
    private const EMAIL_CONSTRAINT_MESSAGE = "L'\email \"atcchoum-du-974@gmail\" n\'est pas valide.";
    private const INVALID_EMAIL_VALUE = "atchoum-du-974@gmail.com";
    private const PASSWORD_REGEX_CONSTRAINT = "Le mot de passe doit contenir au moins 4 caractères";
    private const VALID_EMAIL_VALUE = "atchoum-du-974@gmail.com";
    private const VALID_PASSWORD_VALUE = "Atchoum-du-974";

    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }
//
//    public function testUserEntityIsValid(): void
//    {
//        $user = new User();
//
//        $user->setEmail(self::VALID_EMAIL_VALUE);
//        $user->setPassword(self::VALID_PASSWORD_VALUE);
//
//        $this->validator->getValidationErrors($user, 0);
//    }
//
//    public function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationList
//    {
//        $errors = $this->validator->validate($user);
//
//        $this->assertCount($numberOfExpectedErrors, $errors);
//
//        return $errors;
//    }

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

//    public function testGetTask():void
//    {
//        $value = new Task();
//
////        $response = $this->user->addTask($task);
//
//        dd($this->user->getTasks());
//        if($this->user->getTasks())
//
//        self::assertInstanceOf(User::class, $response);
//        self::assertContains($value, $this->user->getPassword());
//
//    }


}