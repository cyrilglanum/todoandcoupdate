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
    }

    public function testListUserNotAdmin()
    {
        //création du client
        $client = static::createClient();
        //Requête à l'URL prévue
        $crawler = $client->request('GET', '/login');
        //Contrôle des champs prévus
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame(1, $crawler->filter('input[name="email"]')->count());
        static::assertSame(1, $crawler->filter('input[name="password"]')->count());
        //Ajout des valeurs dans le formulaire du bouton de connexion
        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'email1@test.com';
        $form['password'] = 'aaaa';
        //Soumission du formulaire
        $client->submit($form);
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //Redirection
        $crawler = $client->followRedirect();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        //Test d'accès à l'URL /users avec un compte non admin
        $client->request('GET', '/users');
        //Redirection page index car non autorisé
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());
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
        $crawler = $client->click($link);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSame("Modifier User1", $crawler->filter('h1')->text());

        static::assertSame(1, $crawler->filter('input[name="user_edit[username]"]')->count());
        static::assertSame(2, $crawler->filter('input[name="user_edit[roles][]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['user_edit[username]'] = 'Username';
//        $form['user_edit[roles][]'] = 'ROLE_USER';
        $client->submit($form);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertSelectorExists('.alert.alert-success');
        self::assertSelectorTextContains('div.alert.alert-success', "Superbe ! L'utilisateur a bien été modifié.");
    }

}