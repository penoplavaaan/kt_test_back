<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Util\PagerTrait;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
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
class ProductControllerTest extends ApiTestCase
{
    private const PRODUCT_COUNT = 30;
    private const CATEGORY_COUNT = 15;
    private const PRODUCT_PER_CATEGORY = 2;
    private const DEFAULT_PER_PAGE = 15;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testListWithNoParams(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'GET',
            url: '/api/products',
        );

        $response = json_decode(
            $client->getResponse()->getContent()
        );
        $firstId = $response->data[0]->id;

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $firstId);
        $this->assertJsonContains([
            'meta' => [
                'lastPage' => self::PRODUCT_COUNT/self::DEFAULT_PER_PAGE
            ]
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testListWithNegativePage(): void
    {
        $client = static::createClient();

        $client->request(
                method: 'GET',
                url: '/api/products',
                options: [
                    'query' => [
                        'page' => -1,
                        'size' => 15,
                    ]
                ]
            );

        $response = json_decode(
            $client->getResponse()->getContent()
        );
        $firstId = $response->data[0]->id;

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $firstId);
        $this->assertJsonContains([
            'meta' => [
                'lastPage' => self::PRODUCT_COUNT/self::DEFAULT_PER_PAGE
            ]
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface|DecodingExceptionInterface
     */
    #[NoReturn]
    public function testListWithSecondPage(): void
    {
        $client = static::createClient();
        $pageSize = self::DEFAULT_PER_PAGE;
        $page = 2;

        $client->request(
            method: 'GET',
            url: '/api/products',
            options: [
                'query' => [
                    'size' => $pageSize,
                    'page' => $page
                ]
            ]
        );

        $response = json_decode(
            $client->getResponse()->getContent()
        );
        $length = count($response->data);
        $firstId = $response->data[0]->id;
        $lastId = $response->data[$length-1]->id;

        $this->assertResponseIsSuccessful();
        $this->assertEquals($pageSize+1, $firstId);
        $this->assertEquals($pageSize*$page, $lastId);
        $this->assertJsonContains([
            'meta' => [
                'lastPage' => self::PRODUCT_COUNT/self::DEFAULT_PER_PAGE
            ]
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testListWithFilter(): void
    {
        $client = static::createClient();
        $pageSize = self::DEFAULT_PER_PAGE;
        $page = 1;

        $client->request(
                method: 'GET',
                url: '/api/products',
                options: [
                    'query' => [
                        'size' => $pageSize,
                        'page' => $page,
                        'filterBy' => 'category_id',
                        'filterValue' => 1
                    ]
                ]
            );

        $response = json_decode(
            $client->getResponse()->getContent()
        );
        $length = count($response->data);
        $firstId = $response->data[0]->id;
        $lastId = $response->data[$length-1]->id;

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $firstId);
        $this->assertEquals(2, $lastId);
        $this->assertJsonContains([
            'meta' => [
                'lastPage' => (int)ceil(self::PRODUCT_PER_CATEGORY/$pageSize)
            ]
        ]);
    }

    public function testGetStatistics()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/statistics');

        $response = json_decode(
            $client->getResponse()->getContent()
        );

        $totalProductCount = $response->totalProductCount;
        $totalCategoriesCount = $response->totalCategoriesCount;


        $this->assertResponseIsSuccessful();
        $this->assertEquals(self::PRODUCT_COUNT, $totalProductCount);
        $this->assertEquals(self::CATEGORY_COUNT, $totalCategoriesCount);
        /**
         * Т.к. у всех категорий по 2 продукта, берутся 5 "верхних" категорий
         */
        $this->assertEquals($response->leastPopularCategories, $response->mostPopularCategories);
    }

    public function testDownloadStatistics(): void
    {
        $response = static::createClient()
            ->request('GET', '/api/products/statistics/download');

        $this->assertResponseIsSuccessful();
    }

    /** Удаляем все данные перед выполнением, чтобы
     * убедиться, что не выкидывает ошибку
     */
    public function testDownloadStatisticsWithEmptyDb()
    {
        $em = $this->getContainer()->get('doctrine.dbal.default_connection');

        $em->exec('DELETE FROM products');
        $em->exec('DELETE FROM categories');

        static::createClient()
            ->request('GET', '/api/products/statistics/download');

        $this->assertResponseIsSuccessful();
    }
}
