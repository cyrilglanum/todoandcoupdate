<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageControllerTest extends WebTestCase
{
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

        //TODO to implement
        self::assertResponseRedirects('/login');
    }

    public function testCreatePageCreateUserForAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/create/user');

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUsername('flaski');
        $user->setPassword('password');
        $user->setEmail('admin@admin.com');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1', 'Formulaire utilisateur');

    }


}