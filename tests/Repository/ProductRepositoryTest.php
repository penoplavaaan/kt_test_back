<?php

namespace App\Tests\Repository;


use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Entity\ProductRepositoryRead;
use App\Filter\ProductFilter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class ProductRepositoryTest extends KernelTestCase
{
    public function testSave()
    {
        self::bootKernel([
            'environment' => 'test'
        ]);

        $container = static::getContainer();
        $filter = new ProductFilter(null, null);

        /** @var ProductRepositoryPersist $productRepositoryPersist */
        $productRepositoryPersist = $container->get(ProductRepositoryPersist::class);

        /** @var ProductRepositoryRead $productRepositoryRead */
        $productRepositoryRead = $container->get(ProductRepositoryRead::class);

        /** @var CategoryRepositoryRead $categoryRepositoryRead */
        $categoryRepositoryRead = $container->get(CategoryRepositoryRead::class);

        $initialProductCount = $productRepositoryRead->count('', $filter);

        $product = new Product();
        $product->setTitle('Test');
        $product->setDescription('Test');
        $product->setCategory($categoryRepositoryRead->getByName('Test'));
        $product->setWeight('15 kg');

        $productRepositoryPersist->save($product);

        $currentProductCount = $productRepositoryRead->count('', $filter);

        self::assertEquals($initialProductCount+1, $currentProductCount);
    }
}
