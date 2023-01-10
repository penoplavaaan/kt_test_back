<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\String\ByteString;

class ProductFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CategoryRepositoryRead $categoryRepositoryRead,
        private readonly ProductRepositoryPersist $productRepositoryPersist
    )
    {
    }

    public function getDependencies(): array
    {
        return array('App\DataFixtures\CategoryFixture');
    }

    #[NoReturn]
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 15) as $i) {
            $category = $this->categoryRepositoryRead->getById($i);
            self::makeProductWithKilograms($category);
            self::makeProductWithGrams($category);
        }

        $manager->flush();
    }

    private function makeProductWithGrams(Category $category)
    {
        $product = new Product();
        $product->setTitle(self::generateDummyTitle());
        $product->setWeight(self::generateDummyWeight(Product::WEIGHT_GR));
        $product->setCategory($category);
        $product->setDescription(self::generateDummyDescription());

        $this->productRepositoryPersist->save($product);
    }

    private function makeProductWithKilograms(Category $category)
    {
        $product = new Product();
        $product->setTitle(self::generateDummyTitle());
        $product->setWeight(self::generateDummyWeight(Product::WEIGHT_KG));
        $product->setCategory($category);
        $product->setDescription(self::generateDummyDescription());

        $this->productRepositoryPersist->save($product);
    }

    private function generateDummyTitle(): string
    {
        return ByteString::fromRandom(16)->toString();
    }

    private function generateDummyDescription(): string
    {
        return ByteString::fromRandom(128)->toString();
    }

    private function generateDummyWeight(string $unitOfMeasurement): string
    {
        return rand(1,100).' '.$unitOfMeasurement;
    }
}
