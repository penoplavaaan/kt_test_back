<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * ВВОДНЫЕ:
 * Создается 30 экземпляров сущности Product,
 * посредством фикстуры в App\DataFixtures\ProductFixture.php,
 * и 15 экземпляров сущности Category,
 * посредством фикстуры в App\DataFixtures\CategoryFixture.php,
 * по два продукта на каждую категорию
 */
class CategoryControllerTest extends ApiTestCase
{
    private const CATEGORY_COUNT = 15;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[NoReturn]
    public function testList(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/categories');

        $response = json_decode(
            $client->getResponse()->getContent()
        );

        $this->assertResponseIsSuccessful();
        $this->assertCount(self::CATEGORY_COUNT, $response);
    }
}
