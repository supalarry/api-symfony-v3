<?php

namespace App\Tests;

use App\Entity\Orders;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateOrderTest extends WebTestCase
{
    public function test_valid_domestic_order()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '
            {
                "ship_to_address" : {
                    "name" : "John",
                    "surname" : "Doe",
                    "street" : "Palm street 25-7",
                    "state" : "California",
                    "zip" : "60744",
                    "country" : "US",
                    "phone" : "+1 123 123 123"
                },
                "line_items" : [
                    {"id" : 1, "quantity" : 2}
                ]
            }'
        );
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        /* see if keys exists */
        $this->assertArrayHasKey(Orders::ORDER_SHIPPING_DATA, $responseBody);
        $this->assertArrayHasKey(Orders::ORDER_LINE_ITEMS, $responseBody);
        $this->assertArrayHasKey(Orders::ORDER_INFO, $responseBody);

        $this->assertArrayHasKey(Orders::ORDER_OWNER_NAME, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_OWNER_SURNAME, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_STREET, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_STATE, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_ZIP, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_COUNTRY, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_PHONE, $responseBody[Orders::ORDER_SHIPPING_DATA]);

        $this->assertArrayHasKey(Orders::PRODUCT_ID, $responseBody[Orders::ORDER_LINE_ITEMS][0]);
        $this->assertArrayHasKey(Orders::PRODUCT_QUANTITY, $responseBody[Orders::ORDER_LINE_ITEMS][0]);

        $this->assertArrayHasKey(Orders::ORDER_ID, $responseBody[Orders::ORDER_INFO]);
        $this->assertArrayHasKey(Orders::ORDER_PRODUCTION_COST, $responseBody[Orders::ORDER_INFO]);
        $this->assertArrayHasKey(Orders::ORDER_SHIPPING_COST, $responseBody[Orders::ORDER_INFO]);
        $this->assertArrayHasKey(Orders::ORDER_TOTAL_COST, $responseBody[Orders::ORDER_INFO]);
        /* test key values */
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME], "John");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME], "Doe");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET], "Palm street 25-7");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE], "California");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP], "60744");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY], "US");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE], "+1 123 123 123");
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_ID], 2);
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_PRODUCTION_COST], 2000);
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_SHIPPING_COST], 150);
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_TOTAL_COST], 2150);
        /* test value types */
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_ID]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_PRODUCTION_COST]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_SHIPPING_COST]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_TOTAL_COST]);
    }

    public function test_valid_international_order()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users/1/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '
            {
                "ship_to_address" : {
                    "name" : "John",
                    "surname" : "Doe",
                    "street" : "Palm street 25-7",
                    "state" : "California",
                    "zip" : "60744",
                    "country" : "Latvia",
                    "phone" : "+1 123 123 123"
                },
                "line_items" : [
                    {"id" : 1, "quantity" : 2}
                ]
            }'
        );
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), TRUE);
        /* see if keys exists */
        $this->assertArrayHasKey(Orders::ORDER_SHIPPING_DATA, $responseBody);
        $this->assertArrayHasKey(Orders::ORDER_LINE_ITEMS, $responseBody);
        $this->assertArrayHasKey(Orders::ORDER_INFO, $responseBody);

        $this->assertArrayHasKey(Orders::ORDER_OWNER_NAME, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_OWNER_SURNAME, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_STREET, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_STATE, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_ZIP, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_COUNTRY, $responseBody[Orders::ORDER_SHIPPING_DATA]);
        $this->assertArrayHasKey(Orders::ORDER_PHONE, $responseBody[Orders::ORDER_SHIPPING_DATA]);

        $this->assertArrayHasKey(Orders::PRODUCT_ID, $responseBody[Orders::ORDER_LINE_ITEMS][0]);
        $this->assertArrayHasKey(Orders::PRODUCT_QUANTITY, $responseBody[Orders::ORDER_LINE_ITEMS][0]);

        $this->assertArrayHasKey(Orders::ORDER_ID, $responseBody[Orders::ORDER_INFO]);
        $this->assertArrayHasKey(Orders::ORDER_PRODUCTION_COST, $responseBody[Orders::ORDER_INFO]);
        $this->assertArrayHasKey(Orders::ORDER_SHIPPING_COST, $responseBody[Orders::ORDER_INFO]);
        $this->assertArrayHasKey(Orders::ORDER_TOTAL_COST, $responseBody[Orders::ORDER_INFO]);
        /* test key values */
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME], "John");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME], "Doe");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET], "Palm street 25-7");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE], "California");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP], "60744");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY], "Latvia");
        $this->assertEquals($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE], "+1 123 123 123");
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_ID], 2);
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_PRODUCTION_COST], 2000);
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_SHIPPING_COST], 450);
        $this->assertEquals($responseBody[Orders::ORDER_INFO][Orders::ORDER_TOTAL_COST], 2450);
        /* test value types */
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY]);
        $this->assertIsString($responseBody[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_ID]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_PRODUCTION_COST]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_SHIPPING_COST]);
        $this->assertIsInt($responseBody[Orders::ORDER_INFO][Orders::ORDER_TOTAL_COST]);
    }
}
