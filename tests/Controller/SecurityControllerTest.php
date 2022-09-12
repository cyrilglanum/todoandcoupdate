<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    public function testDisplayLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorNotExists('.alert alert-danger');
    }

    public function testBadCredentialsConnectLoginPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => "cyril@glanum.com",
            'password' => 'fakepassword'
        ]);

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorExists('.alert.alert-danger');
        self::assertSelectorTextContains('div.alert.alert-danger', "Invalid credentials.");
    }

    public function testSuccessfulCredentialsConnectLoginPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame(1, $crawler->filter('input[name="email"]')->count());
        static::assertSame(1, $crawler->filter('input[name="password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'cyril@glanum.com';
        $form['password'] = 'aaaa';

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        $this->assertEquals(1, $crawler->filter('h1')->count());

        return $client;
    }

}