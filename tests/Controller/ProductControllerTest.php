<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ProductControllerTest extends ApiTestCase
{
    public function testList(): void
    {
        $response = static::createClient()
            ->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }
    public function testGetStatistics(): void
    {
        $response = static::createClient()
            ->request('GET', '/api/products/statistics/download');

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }

    public function testDownloadStatistics()
    {
        $response = static::createClient()
            ->request('GET', '/api/products/statistics');

        $this->assertResponseIsSuccessful();

    }
}
