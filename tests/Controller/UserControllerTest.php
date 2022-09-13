<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{

    public function testListUserUnauthorized()
    {
        $client = static::createClient();

        $client->request('GET', '/users');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        //After login page ..

        return $client;
    }

    public function testListUserAuthorized()
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

        $crawler = $client->request('GET', '/users');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());

        return $client;
    }

    public function testListUserNotAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame(1, $crawler->filter('input[name="email"]')->count());
        static::assertSame(1, $crawler->filter('input[name="password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'email1@test.com';
        $form['password'] = 'aaaa';

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        $this->assertEquals(1, $crawler->filter('h1')->count());

        $client->request('GET', '/users');
        //redirection to indexPage because not admin
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        //After login page ..

        return $client;
    }

    public function test404()
    {
        $client = static::createClient();
        $client->request('GET', '/index');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }


    public function testEditUserForAdmin()
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

        $crawler = $client->request('GET', '/users');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());

        static::assertSame("Modifier", $crawler->filter('.btn.btn-success.btn-sm')->text());

        $link = $crawler->selectLink('Modifier')->link();
        //deconnexion
        $client->click($link);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
//        $crawler = $client->followRedirect();
//        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        //assert that we are not connected.
        static::assertSame("Modifier", $crawler->filter('body > div:nth-child(2) > div:nth-child(4) > div > div > form > button')->text());

        return $client;
    }
}