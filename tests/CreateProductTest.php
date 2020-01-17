<?php

namespace App\Tests;

use App\Entity\Products;
use App\ProductTypeValidator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class CreateProductTest extends WebTestCase
{
    public function test_valid_request_body()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","title":"aware-wolf", "sku":"100-abc-1000", "cost":1000}'
        );
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        /* see if keys exists */
        $this->assertArrayHasKey(Products::PRODUCT_ID, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_OWNER_ID, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_TYPE, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_TITLE, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_SKU, $responseBody);
        $this->assertArrayHasKey(Products::PRODUCT_COST, $responseBody);
        /* test key values */
        $this->assertEquals($responseBody[Products::PRODUCT_ID], 2);
        $this->assertEquals($responseBody[Products::PRODUCT_OWNER_ID], 1);
        $this->assertEquals($responseBody[Products::PRODUCT_TYPE], "t-shirt");
        $this->assertEquals($responseBody[Products::PRODUCT_TITLE], "aware-wolf");
        $this->assertEquals($responseBody[Products::PRODUCT_SKU], "100-abc-1000");
        $this->assertEquals($responseBody[Products::PRODUCT_COST], 1000);
        /* test value types */
        $this->assertIsInt($responseBody[Products::PRODUCT_ID]);
        $this->assertIsInt($responseBody[Products::PRODUCT_OWNER_ID]);
        $this->assertIsString($responseBody[Products::PRODUCT_TYPE]);
        $this->assertIsString($responseBody[Products::PRODUCT_TITLE]);
        $this->assertIsString($responseBody[Products::PRODUCT_SKU]);
        $this->assertIsInt($responseBody[Products::PRODUCT_COST]);
    }

    public function test_invalid_json_body()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt",,,,,,,,,"title":"aware-wolf", "sku":"100-abc-1000", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey('json', $responseBody);
        $this->assertEquals($responseBody['json'], 'Syntax error');
    }

    public function test_empty_body()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            ''
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey('json', $responseBody);
        $this->assertEquals($responseBody['json'], 'Syntax error');
    }

    public function test_empty_json_object()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_TYPE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TYPE][0], "type key not set");
        $this->assertArrayHasKey(Products::PRODUCT_TITLE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TITLE][0], "title key not set");
        $this->assertArrayHasKey(Products::PRODUCT_SKU, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_SKU][0], "sku key not set");
        $this->assertArrayHasKey(Products::PRODUCT_COST, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_COST][0], "cost key not set");
    }

    public function test_missing_type_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"xxxx":"t-shirt","title":"aware-wolf", "sku":"100-abc-1000", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_TYPE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TYPE][0], "type key not set");
    }

    public function test_invalid_type_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"invalidproduct","title":"aware-wolf", "sku":"100-abc-1000", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_TYPE, $responseBody);

        $productTypeValidator = new ProductTypeValidator();
        $this->assertEquals($responseBody[Products::PRODUCT_TYPE][0], 'Invalid type. Allowed types: ' . $productTypeValidator->getAllowed());
    }

    public function test_missing_title_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","xxxxx":"aware-wolf", "sku":"100-abc-1000", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_TITLE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TITLE][0], "title key not set");
    }

    public function test_invalid_title_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","title":"^^^aware-wolf^^^", "sku":"100-abc-1000", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_TITLE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TITLE][0], "Invalid title. It can only consist of letters, digits and dash(-)");
    }

    public function test_missing_sku_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","title":"aware-wolf", "xxx":"100-abc-1000", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_SKU, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_SKU][0], "sku key not set");
    }

    public function test_duplicate_sku_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","title":"aware-wolf", "sku":"100-abc-999", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_SKU, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_SKU][0], "Invalid SKU. It must be unique, and it appears another product already has it");
    }

    public function test_missing_cost_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","title":"aware-wolf", "sku":"100-abc-1000", "xxxx":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_COST, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_COST][0], "cost key not set");
    }

    public function test_invalid_cost_key()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"type":"t-shirt","title":"aware-wolf", "sku":"100-abc-1000", "cost":1000.00}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_COST, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_COST][0], "Invalid cost. It must be an integer describing price with smallest money unit");
    }

    public function test_multiple_errors_missing_type_key_and_invalid_title()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"xxxx":"t-shirt","title":"$$$$$$$$$aware-wolf", "sku":"100-abc-999", "cost":1000}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        $this->assertArrayHasKey(Products::PRODUCT_TYPE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TYPE][0], "type key not set");
        $this->assertArrayHasKey(Products::PRODUCT_TITLE, $responseBody);
        $this->assertEquals($responseBody[Products::PRODUCT_TITLE][0], "Invalid title. It can only consist of letters, digits and dash(-)");
    }
}
