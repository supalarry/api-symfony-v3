<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateUserTest extends WebTestCase
{
    /** @test */
    public function valid_request_body()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"John","surname":"Doe"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}
