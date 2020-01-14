<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use App\JsonToArray;
use App\Exception\JsonToArrayException;

class JsonToArrayTest extends TestCase
{
    /** @test */
    public function valid_json()
    {
        $json_body = "{
            \"name\":\"john\",
            \"surname\":\"doe\"
        }";
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            $json_body);

        $json_to_array = new JsonToArray($request);

        $request_decoded = $json_to_array->retrieve();

        $this->assertArrayHasKey('name', $request_decoded);
        $this->assertArrayHasKey('surname', $request_decoded);
        $this->assertEquals($request_decoded['name'], 'john');
        $this->assertEquals($request_decoded['surname'], 'doe');
    }

    /** @test */
    public function invalid_json()
    {
        /* we have comma after "doe" resulting in an invalid json when it is decoded */
        $json_body = "{
            \"name\":\"john\",
            \"surname\":\"doe\",
        }";

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            $json_body);

        $json_to_array = new JsonToArray($request);

        $this->expectException(JsonToArrayException::class);

        $request_decoded = $json_to_array->retrieve();

    }

    /** @test */
    public function empty_json_body()
    {
        $json_body = "{}";

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            $json_body);

        $json_to_array = new JsonToArray($request);

        $request_decoded = $json_to_array->retrieve();

        $this->assertEmpty($request_decoded);
    }

    /** @test */
    public function empty_json()
    {
        $json_body = "";

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            $json_body);

        $json_to_array = new JsonToArray($request);

        $this->expectException(JsonToArrayException::class);

        $request_decoded = $json_to_array->retrieve();
    }
}
