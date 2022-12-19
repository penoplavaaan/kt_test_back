<?php

declare(strict_types=1);

namespace App\Repository;

use App\Doctrine\DoctrineRepository;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Entity\ProductRepositoryRead;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use PhpParser\Builder;

final class ProductRepository extends DoctrineRepository implements ProductRepositoryRead, ProductRepositoryPersist
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return Product::class;
    }

    /**
     * @return Product[]
     */
    public function list(): array
    {
        return $this->em()
            ->createQueryBuilder()
            ->select([
                'products.id',
                'products.title',
                'products.description',
                'products.categoryId'
            ])
            ->from($this->getEntityName(), 'products')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function index(string $query, ?string $filterBy, ?string $filterValue, int $limit, int $offset): array
    {
        $query = $this->em()
            ->createQueryBuilder()
            ->select([
                'products.id',
                'products.title',
                'products.description',
                'products.category_id',
                'categories.name'
            ])
            ->from($this->getEntityName(), 'products')
            ->leftJoin('products.category', 'categories')
            ->where('products.title LIKE :query')
            ->orWhere('products.description LIKE :query')
            ->setParameter('query', '%'.$query.'%');

        if (!is_null($filterBy) && !is_null($filterValue)){
            $query = $query->andWhere('products.category_id = :filterValue')
                ->setParameter('filterValue', $filterValue);
        }

        return $query->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $query
     * @return int
     */
    public function count(string $query, ?string $filterBy, ?string $filterValue): int
    {
        $query = $this->em()
            ->createQueryBuilder()
            ->select('COUNT(products)')
            ->where('products.title LIKE :query')
            ->orWhere('products.description LIKE :query')
            ->from($this->getEntityName(), 'products')
            ->setParameter('query', '%'.$query.'%');

        if (!is_null($filterBy) && !is_null($filterValue)){
            $query = $query->andWhere('products.category_id = :filterValue')
                ->setParameter('filterValue', $filterValue);
        }

        return $query->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product): void
    {
        $this->persist($product);
    }
}