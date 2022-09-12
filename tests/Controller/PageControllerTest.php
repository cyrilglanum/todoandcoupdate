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

        $crawler = $client->request('GET', '/create/user');

        static::assertSame("Formulaire utilisateur", $crawler->filter('h1')->text());
        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
        static::assertSame(3, $crawler->filter('input[name="user[roles][]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'cyrilglanum';
        $form['user[email]'] = 'cyril@glanum.com';
        $form['user[password][first]'] = 'bbbb';
        $form['user[password][second]'] = 'bbbb';

        $client->submit($form);

        return $client;
    }

//    public function testBadCreateUser()
//    {
//
//    }


}