<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    use FixturesTrait;

    public function testCount()
    {
        $client = static::createClient();
        $userRepository = $this->getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('email1@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/task_list');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a', 'To Do List app');
    }

}