<?php

namespace App\Tests\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class UploadControllerTest extends WebTestCase
{
    /** Проверяем что при загрузке файла
     * создается задача в очереди на чтение файла
     * @throws Exception
     */
    public function testUploadSuccess(): void
    {
        $client = $this->createClient([
            'environment' => 'test'
        ]);
        $container = $this->getContainer();

        $testUploadPath = $container->getParameter('uploads_directory_to_read');
        $realUploadPath = $container->getParameter('test_uploads_directory');

        copy($testUploadPath.'/testFileToCopy.xml',$testUploadPath.'/importTest.xml');

        $xmlFile = new UploadedFile(
            path: $testUploadPath.'/importTest.xml',
            originalName: 'importTest.xml',
            mimeType: 'text/xml',
        );

        $client->request(
            'POST',
            '/api/upload',
            ['Content-Type'=>'text/xml'],
            ['products' => $xmlFile]
        );

        $this->assertResponseIsSuccessful();

        $uploadFileName = json_decode($client->getResponse()->getContent())->filename;

        unlink($realUploadPath.'/'.$uploadFileName);

        /* @var InMemoryTransport $transport */
        $transport = $container->get('messenger.transport.async_in_memory');

        $messageCount = count($transport->getSent());
        $this->assertEquals(1, $messageCount);
    }

    public function testUploadWithNoFile(): void
    {
        $client = $this->createClient([
            'environment' => 'test'
        ]);

        $client->request(
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

        $pathToUpload = $this->getContainer()->getParameter('uploads_directory_to_read');

        copy($pathToUpload.'/testImageToCopy.jpeg',$pathToUpload.'/capybara.jpeg');

        $image = new UploadedFile(
            path: $pathToUpload.'/capybara.jpeg',
            originalName: 'capybara.jpeg',
            mimeType: 'image/jpeg',
        );

        $client->request(
            'POST',
            '/api/upload',
            ['Content-Type'=>'image/jpeg'],
            ['products' => $image]
        );

        unlink($pathToUpload.'/capybara.jpeg');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    /** Симулируем работу метода UploadController->upload()
     * без выполнения задач в очереди
     */
    public function testGetUploadTasksCount(): void
    {
        $client = $this->createClient([
            'environment' => 'test'
        ]);
        $container = $this->getContainer();

        $testUploadPath = $container->getParameter('uploads_directory_to_read');
        $realUploadPath = $container->getParameter('test_uploads_directory');

        copy($testUploadPath.'/testFileToCopy.xml',$testUploadPath.'/importTest.xml');

        $xmlFile = new UploadedFile(
            path: $testUploadPath.'/importTest.xml',
            originalName: 'importTest.xml',
            mimeType: 'text/xml',
        );

        $client->request(
            'POST',
            '/api/upload',
            ['Content-Type'=>'text/xml'],
            ['products' => $xmlFile]
        );
        $this->assertResponseIsSuccessful();
        $uploadFileName = json_decode(
            $client->getResponse()->getContent()
        )->filename;

        $this->ensureKernelShutdown();
        $client = $this->createClient([
            'environment' => 'test'
        ]);
        $client->request('GET', '/api/upload-tasks-count');
        $taskCount = json_decode(
            $client->getResponse()->getContent()
        );
        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $taskCount);

        unlink($realUploadPath.'/'.$uploadFileName);

        $this->ensureKernelShutdown();
        $client = $this->createClient([
            'environment' => 'test'
        ]);
        $client->request('GET', '/api/upload-tasks-count');
        $taskCount = json_decode(
            $client->getResponse()->getContent()
        );
        $this->assertResponseIsSuccessful();
        $this->assertEquals(0, $taskCount);
    }
}
