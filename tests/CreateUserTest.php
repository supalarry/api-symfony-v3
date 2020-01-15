<?php

namespace App\Tests;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class CreateUserTest extends WebTestCase
{
    public function test_valid_request_body()
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

    public function test_invalid_json_body()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"John",,,,,,,,"surname":"Doe"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey('json', $responseBody);
        $this->assertEquals($responseBody['json'], "Invalid JSON body");
    }

    public function test_missing_name_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"xxxx":"John","surname":"Doe"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Users::USER_NAME, $responseBody);
        $this->assertEquals($responseBody[Users::USER_NAME][0], "name key not set");
    }

    public function test_invalid_name_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"John00000","surname":"Doe"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Users::USER_NAME, $responseBody);
        $this->assertEquals($responseBody[Users::USER_NAME][0], "Invalid name. It can only consist of letters and can not be empty");
    }

    public function test_missing_surname_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"John","xxxxxxx":"Doe"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Users::USER_SURNAME, $responseBody);
        $this->assertEquals($responseBody[Users::USER_SURNAME][0], "surname key not set");
    }

    public function test_invalid_surname_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"John","surname":"Doe00000"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Users::USER_SURNAME, $responseBody);
        $this->assertEquals($responseBody[Users::USER_SURNAME][0], "Invalid surname. It can only consist of letters and can not be empty");
    }

    public function test_multiple_errors_missing_both_keys()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"xxxx":"John","xxxxxxx":"Doe"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Users::USER_NAME, $responseBody);
        $this->assertArrayHasKey(Users::USER_SURNAME, $responseBody);
        $this->assertEquals($responseBody[Users::USER_NAME][0], "name key not set");
        $this->assertEquals($responseBody[Users::USER_SURNAME][0], "surname key not set");
    }

    public function test_multiple_errors_missing_name_key_and_invalid_surname()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"xxxx":"John","surname":"Doe00000"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Users::USER_NAME, $responseBody);
        $this->assertArrayHasKey(Users::USER_SURNAME, $responseBody);
        $this->assertEquals($responseBody[Users::USER_NAME][0], "name key not set");
        $this->assertEquals($responseBody[Users::USER_SURNAME][0], "Invalid surname. It can only consist of letters and can not be empty");
    }
}
