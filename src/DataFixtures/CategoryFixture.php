<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class CategoryFixture extends Fixture
{
    public function __construct(
        private readonly CategoryRepositoryPersist $categoryRepositoryPersist
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (range(1, 15) as $ignored) {
            self::makeCategory();
        }
    }

    public function makeCategory()
    {
        $category = new Category();
        $category->setName(ByteString::fromRandom(16)->toString());
        $this->categoryRepositoryPersist->save($category);
    }
}
