<?php

namespace App\Tests;

use App\Entity\Users;
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

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        /* see if keys exists */
        $this->assertArrayHasKey(Users::USER_ID, $responseBody);
        $this->assertArrayHasKey(Users::USER_NAME, $responseBody);
        $this->assertArrayHasKey(Users::USER_SURNAME, $responseBody);
        $this->assertArrayHasKey(Users::USER_BALANCE, $responseBody);
        /* test key values */
        $this->assertEquals($responseBody[Users::USER_ID], 1);
        $this->assertEquals($responseBody[Users::USER_NAME], "John");
        $this->assertEquals($responseBody[Users::USER_SURNAME], "Doe");
        $this->assertEquals($responseBody[Users::USER_BALANCE], 10000);
        /* test value types */
        $this->assertIsInt($responseBody['id']);
        $this->assertIsString($responseBody['name']);
        $this->assertIsString($responseBody['surname']);
        $this->assertIsInt($responseBody['balance']);
    }

    /** @test */
    public function invalid_json_body()
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

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);

    }
}
