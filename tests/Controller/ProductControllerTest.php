<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testListWithNoParams(): void
    {
        $response = static::createClient()
            ->request(
                method: 'GET',
                uri: '/api/products',
            );

        $this->assertResponseIsSuccessful();
    }

    public function testListWithNegativePage(): void
    {
        $response = static::createClient()
            ->request(
                method: 'GET',
                uri: '/api/products',
                parameters: [
                    'page' => -1
                ]
            );

        $this->assertResponseIsSuccessful();
    }

    public function testListWithNotFirstPage(): void
    {
        $response = static::createClient()
            ->request(
                method: 'GET',
                uri: '/api/products',
                parameters: [
                    'page' => 2
                ]
            );

        $this->assertResponseIsSuccessful();
    }

    public function testListWithFilter(): void
    {
        $response = static::createClient()
            ->request(
                method: 'GET',
                uri: '/api/products',
                parameters: [
                    'filterBy' => 'category_id',
                    'filterValue' => 1
                ]
            );

        $this->assertResponseIsSuccessful();
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
