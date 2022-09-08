<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
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

}