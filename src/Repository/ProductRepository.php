<?php

declare(strict_types=1);

namespace App\Repository;

use App\Doctrine\DoctrineRepository;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Entity\ProductRepositoryRead;
use App\Filter\ProductFilter;
use App\Resources\Statistics\ShowStatisticsResource;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use \App\Filter\Filter;

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
    public function index(string $query, ProductFilter|Filter $filter, int $limit, int $offset): array
    {
        $query = $this->em()
            ->createQueryBuilder()
            ->select([
                'products.id',
                'products.title',
                'products.description',
                'products.weight',
                'products.category_id',
                'categories.name'
            ])
            ->from($this->getEntityName(), 'products')
            ->leftJoin('products.category', 'categories')
            ->where('products.title LIKE :query')
            ->orWhere('products.description LIKE :query')
            ->setParameter('query', '%' . $query . '%');

        $filterBy = $filter->getFilterBy();
        $filterValue = $filter->getFilterValue();

        if (!is_null($filterBy) && !is_null($filterValue)) {
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
    public function count(string $query, ProductFilter|Filter $filter): int
    {
        $query = $this->em()
            ->createQueryBuilder()
            ->select('COUNT(products)')
            ->where('products.title LIKE :query')
            ->orWhere('products.description LIKE :query')
            ->from($this->getEntityName(), 'products')
            ->setParameter('query', '%' . $query . '%');

        $filterBy = $filter->getFilterBy();
        $filterValue = $filter->getFilterValue();

        if (!is_null($filterBy) && !is_null($filterValue)) {
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

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     * @throws NoResultException
     */
    public function getStatistics(): ShowStatisticsResource
    {
        $productsEntity = $this->getEntityName();
        $categoriesEntity = Category::class;

        $totalProductCount = $this->em()
            ->createQueryBuilder()
            ->select('COUNT(products)')
            ->from($productsEntity, 'products')
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);

        $totalProductsWeight = $this->em()
                ->createQuery("SELECT SUM(e.weight) AS weight_count FROM $productsEntity e ")
                ->getSingleScalarResult() / 1000;

        $avgProductsWeight = (float)$this->em()
                ->createQuery("SELECT AVG(e.weight) AS weight_avg FROM $productsEntity e ")
                ->getSingleScalarResult() / 1000;

        $totalCategoriesCount = $this->em()
            ->createQueryBuilder()
            ->select('COUNT(category)')
            ->from($categoriesEntity, 'category')
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);

        $mostPopularCategories = $this->em()
            ->getConnection()
            ->executeQuery("SELECT count(category_id), categories.name FROM categories
LEFT JOIN products p on categories.id = p.category_id
GROUP BY category_id, categories.name
ORDER BY count(category_id) DESC
LIMIT 5")
            ->fetchAllAssociative();

        $leastPopularCategories = $this->em()
            ->getConnection()
            ->executeQuery("SELECT count(category_id), categories.name FROM categories
LEFT JOIN products p on categories.id = p.category_id
GROUP BY category_id, categories.name
ORDER BY count(category_id) ASC
LIMIT 5")
            ->fetchAllAssociative();

        return new ShowStatisticsResource(
            totalProductCount: $totalProductCount,
            totalCategoriesCount: $totalCategoriesCount,
            totalProductsWeightKg: $totalProductsWeight,
            avgProductsWeight: $avgProductsWeight,
            mostPopularCategories: $mostPopularCategories,
            leastPopularCategories: $leastPopularCategories
        );
    }
}