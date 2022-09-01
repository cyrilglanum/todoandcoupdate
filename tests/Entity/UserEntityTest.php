<?php

namespace App\Tests\Entity;

use App\DataFixtures\UserFixtures;
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

    private  $validator;

//    public function setUp(): void
//    {
//        $this->validator = self::bootKernel()->getContainer()->get('validator');
//    }
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

    public function testGetSetUser()
    {
        $task = new User();
        $content = "cyrilg@gmail.com";

        $task->setEmail($content);
        $this->assertEquals("cyrilg@gmail.com", $task->getEmail());
    }


}