<?php

declare(strict_types=1);

namespace App\Repository;

use App\Doctrine\DoctrineRepository;
use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Employee;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Entity\ProductRepositoryRead;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;

final class CategoryRepository extends DoctrineRepository implements CategoryRepositoryRead, CategoryRepositoryPersist
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return Category::class;
    }

    /**
     * @return Category[]
     */
    public function list(): array
    {
        return $this->em()
            ->createQueryBuilder()
            ->select([
                'categories.id',
                'categories.name',
            ])
            ->from($this->getEntityName(), 'categories')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $name
     *
     * @return Category|null
     */
    public function getByName(string $name): ?Category
    {
        return $this->repository()->findOneBy(['name' => $name]);
    }

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function getById(int $id): ?Category
    {
        return $this->repository()->findOneBy(['id' => $id]);
    }

    /**
     * @param Category $category
     * @return void
     */
    public function save(Category $category): void
    {
        $this->persist($category);
    }
}