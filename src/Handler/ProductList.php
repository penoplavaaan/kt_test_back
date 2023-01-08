<?php

namespace App\Handler;

use App\Entity\ProductRepositoryRead;
use App\Filter\ProductFilter;
use App\Resources\Product\ProductPaginatedResource;
use App\Util\PagerTrait;

final class ProductList
{
    use PagerTrait;

    public function __construct(
        private readonly ProductRepositoryRead $repositoryRead,
    )
    {
    }

    /**
     * @param string $query
     * @param string|null $filterBy
     * @param string|null $filterValue
     * @param int|null $page
     * @param int|null $limit
     * @return ProductPaginatedResource
     */
    public function handle(
        string $query,
        ?string $filterBy,
        ?string $filterValue,
        ?int $page = 1,
        ?int $limit = 15
    ): ProductPaginatedResource
    {
        $page = $this->getPage($page);
        $limit = $this->getLimit($limit);
        $offset = $this->getOffset($page, $limit);

        $filter = new ProductFilter(
            filterBy: $filterBy,
            filterValue: $filterValue
        );

        $lastPage = $this->getLastPage(
            $this->repositoryRead->count($query, $filter),
            $limit
        );

        return new ProductPaginatedResource(
            $this->repositoryRead->index($query, $filter, $limit, $offset),
            $lastPage
        );
    }


}