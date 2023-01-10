<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CategoryControllerTest extends ApiTestCase
{
    public function testList(): void
    {
        $response = static::createClient()->request('GET', '/api/categories');

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }
}
