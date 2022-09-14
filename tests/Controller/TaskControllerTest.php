<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{


    public function testLoginForTask()
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

        //After login page ..

        return $client;
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());
    }

    public function test404()
    {
        $client = static::createClient();
        $client->request('GET', '/index');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testLoginForCreateTask()
    {
        $client = $this->testLoginForTask();
        $crawler = $client->request('GET', '/task_create');

        static::assertSame("Ajouter une tâche", $crawler->filter('h3')->text());
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'Test de titre à rajouter';
        $form['task[content]'] = 'Content de test unitaire';

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        self::assertSelectorExists('.alert.alert-success');
        self::assertSelectorTextContains('div.alert.alert-success', "Superbe ! La tâche a été bien été ajoutée.");
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorNotExists('.alert alert-danger');
    }

    public function testLogoutForCreateTask()
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
        static::assertSame("Se déconnecter", $crawler->filter('a.pull-right.btn.btn-info.deconnexion')->text());

        $link = $crawler->selectLink('Se déconnecter')->link();
        //deconnexion
        $client->click($link);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $crawler = $client->followRedirect();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        //assert that we are not connected.
        static::assertSame("Se connecter", $crawler->filter('body > div:nth-child(2) > div:nth-child(1) > a')->text());
    }

    public function testRemoveTaskFromUser()
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
        $crawler = $client->request('GET', '/task_list');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Supprimer", $crawler->filter('.btn.btn-danger.btn-sm.pull-right')->text());

        $form = $crawler->selectButton('Supprimer')->form();

        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertSelectorExists('.alert.alert-success');
        self::assertSelectorTextContains('div.alert.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }

    public function testTaskEdit()
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
        $crawler = $client->request('GET', '/task_list');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        static::assertSelectorExists('body > div:nth-child(2) > div:nth-child(4) > div > div > div:nth-child(2) > div > div.caption > h4:nth-child(2) > a');

        $link = $crawler->selectLink('email1@test.com')->link();
        //deconnexion
        $crawler = $client->click($link);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['task[title]'] = 'Test de titre à rajouter';
        $form['task[content]'] = 'Content de test unitaire';
        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertSelectorExists('.alert.alert-success');
        self::assertSelectorTextContains('div.alert.alert-success', "Superbe ! La tâche a bien été modifiée.");
    }
}