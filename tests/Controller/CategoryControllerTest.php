<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CategoryControllerTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testList(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/categories');

        $response = json_decode(
            $client->getResponse()->getContent()
        );

        dd($response);

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }
}
