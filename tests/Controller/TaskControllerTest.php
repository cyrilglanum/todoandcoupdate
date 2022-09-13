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

//    public function testDisplayCreateTaskPage()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/login');
//
////        $form = $crawler->selectButton('Créer une nouvelle tâche');
////        $crawler =$crawler->selectButton('Créer une nouvelle tâche')->link());
////        $client->submit($form);
//
//        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
//        $client->followRedirect();
//
////        self::assertSelectorExists('.btn');
////        self::assertSelectorTextContains('.btn', 'Retour à la liste des tâches');
////        self::assertResponseStatusCodeSame(Response::HTTP_OK);
////        self::assertSelectorNotExists('.alert alert-danger');
//    }

    public function testH1IndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

//    public function testListActionWithoutLogin()
//    {
    // If the user isn't logged, should redirect to the login page
//        $client = static::createClient();
//        $client->request('GET', '/tasks');
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//
//        // Test if login field exists
//        static::assertSame(1, $crawler->filter('input[name="email"]')->count());
//        static::assertSame(1, $crawler->filter('input[name="password"]')->count());
//    }

//    public function testListAction()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks');
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//    }
//
//    public function testCreateAction()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/create');
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//
//        // Test if creation page field exists
//        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
//        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());
//
//        $form = $crawler->selectButton('Ajouter')->form();
//        $form['task[title]'] = 'Nouvelle tâche';
//        $form['task[content]'] = 'Ceci est une tâche crée par un test';
//        $client->submit($form);
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//    }
//
//    // Edition d'une tâche par un utilisateur simple
//    public function testEditAction()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/1/edit');
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//
//        // Test if creation page field exists
//        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
//        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());
//
//        $form = $crawler->selectButton('Modifier')->form();
//        $form['task[title]'] = 'Modification de tache';
//        $form['task[content]'] = 'Je modifie une tache';
//        $client->submit($form);
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//    }
//
//    // Suppression d'une tâche crée par un utiliseur simple et supprimé par le même auteur
//    public function testDeleteTaskAction()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/2/delete');
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//
//        // Test if success message is displayed
//        static::assertContains("Superbe ! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
//    }
//
//    public function testDeleteTaskActionWhereSimpleUserIsNotAuthor()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/4/delete');
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//
//        // Test if success message is displayed
//        static::assertContains("Oops ! Seul l'auteur de la tâche ou un admin peut la supprimer !", $crawler->filter('div.alert.alert-danger')->text());
//    }
//
//    public function testDeleteTaskActionWithSimpleUserWhereAuthorIsAnonymous()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/3/delete');
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//
//        // Test if success message is displayed
//        static::assertContains("Oops ! Seul un admin peut supprimer une tâche de l'utilisateur anonyme !", $crawler->filter('div.alert.alert-danger')->text());
//    }
//
//    public function testDeleteTaskActionWhereItemDontExists()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/-100/delete');
//        static::assertSame(404, $client->getResponse()->getStatusCode());
//    }
//
//    public function testToggleTaskAction()
//    {
//        $securityControllerTest = new SecurityControllerTest();
//        $client = $securityControllerTest->testLogin();
//
//        $crawler = $client->request('GET', '/tasks/1/toggle');
//        static::assertSame(302, $client->getResponse()->getStatusCode());
//
//        $crawler = $client->followRedirect();
//        static::assertSame(200, $client->getResponse()->getStatusCode());
//    }


}