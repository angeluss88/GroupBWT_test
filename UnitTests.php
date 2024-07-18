<?php

use PHPUnit\Framework\TestCase;

$argv[1] = 'emptyInput.txt';
require 'app.php';

class UnitTests extends TestCase
{
    public function testGetFinalCoef()
    {
        $this->assertEquals(0.01, getFinalCoef('AT'));
        $this->assertEquals(0.02, getFinalCoef('US'));
    }

    public function testIsEu()
    {
        $this->assertTrue(isEu('AT'));
        $this->assertFalse(isEu('US'));
    }

    public function testHandleRequest()
    {
        $url = 'https://jsonplaceholder.typicode.com/posts/1';
        $response = handleRequest($url);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);

        $result =  [
            "userId" => 1,
            "id" => 1,
            "title" => "sunt aut facere repellat provident occaecati excepturi optio reprehenderit",
            "body" => "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto"
        ];

        $this->assertSame($result, $response);
    }

    public function testGetBinData()
    {
        $bin = '45717360';
        $response = getBinData($bin);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('country', $response);

        $result = [
            "number" => [],
            "scheme" => "visa",
            "type" => "debit",
            "brand" => "Visa Classic",
            "country" => [
               "numeric" => "208",
               "alpha2" => "DK",
               "name" => "Denmark",
               "emoji" => "🇩🇰",
               "currency" => "DKK",
               "latitude" => 56,
               "longitude" => 10
            ],
            "bank" => [
               "name" => "Jyske Bank A/S"
            ]
        ];

        $this->assertSame($result, $response);
    }

    public function testGetRates()
    {
        $rates = getRates();
        $this->assertIsArray($rates);
        $this->assertArrayHasKey('USD', $rates);
    }
}
