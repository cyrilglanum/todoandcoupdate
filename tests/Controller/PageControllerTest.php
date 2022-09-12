<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    public function testHelloPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1IndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

    public function testCreatePageIsAdminRequired()
    {
        $client = static::createClient();
        $client->request('GET', '/create/user');

        self::assertResponseRedirects('/login');
    }

    public function testCreateUserWithValidDatas()
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request('POST', '/login', [
            '_csrf_token' => $csrfToken,
            '_username' => "cyril@glanum.com",
            '_password' => 'aaaa'
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1', 'Formulaire utilisateur');

        $users = $this->loadFixtureFiles([__DIR__.'/users.yaml']);

//        $user = new User([''])


        $this->login($client,new User());

        $crawler = $client->request('GET', '/create/user');


        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
        static::assertSame(3, $crawler->filter('input[name="user[roles][]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request('POST', '/create/user', [
            '_csrf_token' => $csrfToken,
            'user[username]' => "testajout",
            'user[email]' => "test@ajout.com",
            'user[roles][]' => "ROLE_USER",
            'user[password][first]' => "aaaa",
            'user[password][second]' => "aaaa",
        ]);

//        dd($client);
        
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('h1')->count());

//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
//        self::assertResponseRedirects();
//        $client->followRedirect();

//        self::assertResponseStatusCodeSame(Response::HTTP_OK);
//        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());
//
//        $this->assertEquals(1, $crawler->filter('h1')->count());

        return $client;

    }

//    public function testBadCreateUser()
//    {
//
//    }


}