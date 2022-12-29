<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UploadControllerTest extends ApiTestCase
{
    public function testList(): void
    {
        $response = static::createClient()
            ->request('POST', '/api/upload');

        $this->assertResponseIsUnprocessable();
//        $this->assertJsonContains(['@id' => '/']);
    }
    public function testGetUploadTasksCount(): void
    {
        $response = static::createClient()
            ->request('GET', '/api/upload-tasks-count');

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }
}
