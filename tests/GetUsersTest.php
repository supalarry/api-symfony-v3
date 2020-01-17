<?php

namespace App\Tests;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUsersTest extends WebTestCase
{
    public function test_get_users()
    {
        $client = static::createClient();

        $client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertIsArray($responseBody);
        $this->assertIsArray($responseBody[0]);
        $this->assertEquals($responseBody[0][Users::USER_ID], 1);
        $this->assertEquals($responseBody[0][Users::USER_NAME], "John");
        $this->assertEquals($responseBody[0][Users::USER_SURNAME], "Doe");
        $this->assertEquals($responseBody[0][Users::USER_BALANCE], 10000);
    }
}
