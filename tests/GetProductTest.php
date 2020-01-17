<?php

namespace App\Tests;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetProductTest extends WebTestCase
{
    public function test_valid_owner_valid_product()
    {
        $client = static::createClient();

        $client->request('GET', '/users/1/products/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);

        $this->assertIsArray($responseBody);
        /* see if keys exists */
        $this->assertArrayHasKey(Products::PRODUCT_ID, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_OWNER_ID, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_TYPE, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_TITLE, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_SKU, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_COST, $responseBody);
        /* test key values */
        $this->assertEquals($responseBody[Products::PRODUCT_ID], 1);
        $this->assertEquals($responseBody[Products::PRODUCT_OWNER_ID], 1);
        $this->assertEquals($responseBody[Products::PRODUCT_TYPE], "t-shirt");
        $this->assertEquals($responseBody[Products::PRODUCT_TITLE], "much shirt, such style!");
        $this->assertEquals($responseBody[Products::PRODUCT_SKU], "100-abc-999");
        $this->assertEquals($responseBody[Products::PRODUCT_COST], 1000);
        /* test value types */
        $this->assertIsInt($responseBody[Products::PRODUCT_ID]);
        $this->assertIsInt($responseBody[Products::PRODUCT_OWNER_ID]);
        $this->assertIsString($responseBody[Products::PRODUCT_TYPE]);
        $this->assertIsString($responseBody[Products::PRODUCT_TITLE]);
        $this->assertIsString($responseBody[Products::PRODUCT_SKU]);
        $this->assertIsInt($responseBody[Products::PRODUCT_COST]);
    }

    public function test_valid_owner_invalid_product()
    {
        $client = static::createClient();

        $client->request('GET', '/users/1/products/100000');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);

        $this->assertNull($responseBody);
    }

    public function test_invalid_owner_invalid_product()
    {
        $client = static::createClient();

        $client->request('GET', '/users/100000/products/100000');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);

        $this->assertNull($responseBody);
    }
}
