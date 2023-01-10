<?php

namespace App\Tests\Repository;


use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class CategoryRepositoryTest extends KernelTestCase
{
    public function testSave()
    {
        self::bootKernel([
            'environment' => 'test'
        ]);

        $container = static::getContainer();

        /** @var CategoryRepositoryPersist $categoryRepositoryPersist */
        $categoryRepositoryPersist = $container->get(CategoryRepositoryPersist::class);

        /** @var CategoryRepositoryRead $categoryRepositoryRead */
        $categoryRepositoryRead = $container->get(CategoryRepositoryRead::class);

        $category = new Category();
        $category->setName('Test');

        $categoryRepositoryPersist->save($category);

        $categoryRetrievedByName = $categoryRepositoryRead->getByName('Test');

        $categoryRetrievedById = $categoryRepositoryRead->getById($categoryRetrievedByName->getId());

        assertEquals($categoryRetrievedByName, $categoryRetrievedById);
    }
}
