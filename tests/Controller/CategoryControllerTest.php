<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testList(): void
    {
        $response = static::createClient()->request('GET', '/api/categories');

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }
}
