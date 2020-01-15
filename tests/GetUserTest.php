<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUserTest extends WebTestCase
{
    public function test_invalid_json_body()
    {
        $client = static::createClient();

        $client->request('GET', '/users/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
    }
}
