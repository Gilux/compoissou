<?php

namespace BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilisateurControllerControllerTest extends WebTestCase
{
    public function testProfil()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'utilisateurs/profil');
    }

}
