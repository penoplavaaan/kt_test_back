<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UploadControllerTest extends WebTestCase
{
    public function testUploadSuccess(): void
    {
        $client = $this->createClient([
            'environment' => 'test'
        ]);

        $uploadPath = $this->getContainer()->getParameter('test_uploads_directory');

        copy($uploadPath.'/testFileToCopy.xml',$uploadPath.'/importTest.xml');

        $xmlFile = new UploadedFile(
            path: $uploadPath.'/importTest.xml',
            originalName: 'importTest.xml',
            mimeType: 'text/xml',
        );

        $response = $client->request(
            'POST',
            '/api/upload',
            ['Content-Type'=>'text/xml'],
            ['products' => $xmlFile]
        );


        $this->assertResponseIsSuccessful();
    }

    public function testUploadWithNoFile(): void
    {
        $client = $this->createClient([
            'environment' => 'test'
        ]);

        $uploadPath = $this->getContainer()->getParameter('test_uploads_directory');

        $response = $client->request(
            'POST',
            '/api/upload',
            ['Content-Type'=>'text/xml'],
        );


        $this->assertResponseIsUnprocessable();
    }

    public function testUploadWithNonXmlFile(): void
    {
        $client = $this->createClient([
            'environment' => 'test'
        ]);

        $uploadPath = $this->getContainer()->getParameter('test_uploads_directory');

        copy($uploadPath.'/testImageToCopy.jpeg',$uploadPath.'/capybara.jpeg');

        $image = new UploadedFile(
            path: $uploadPath.'/capybara.jpeg',
            originalName: 'capybara.jpeg',
            mimeType: 'image/jpeg',
        );

        $response = $client->request(
            'POST',
            '/api/upload',
            ['Content-Type'=>'image/jpeg'],
            ['products' => $image]
        );

        unlink($uploadPath.'/capybara.jpeg');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    public function testGetUploadTasksCount(): void
    {
        $response = static::createClient()
            ->request('GET', '/api/upload-tasks-count');

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['@id' => '/']);
    }
}
