<?php

namespace BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SignalementControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/signalements/list');
    }

    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/signalements/add');
    }

}
