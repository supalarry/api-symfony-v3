<?php

namespace App\Tests;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUserTest extends WebTestCase
{
    public function test_valid_id()
    {
        $client = static::createClient();

        $client->request('GET', '/users/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

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

    public function test_invalid_id()
    {
        $client = static::createClient();

        $client->request('GET', '/users/1000000');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);

        $this->assertNull($responseBody);
    }
}
