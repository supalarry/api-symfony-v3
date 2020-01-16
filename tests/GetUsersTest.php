<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUsersTest extends WebTestCase
{
    public function test_users_no_slash()
    {
        $client = static::createClient();

        $client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
    }
}
