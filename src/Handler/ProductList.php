<?php

namespace App\Handler;

use App\Entity\Employee;
use App\Entity\ProductRepositoryRead;
use App\Resources\Product\ProductPaginatedResource;
use App\Util\PagerTrait;
use App\Util\Filterable;

final class ProductList implements Filterable
{
    use PagerTrait;

    public function __construct(
        private readonly ProductRepositoryRead $repositoryRead,
    )
    {
    }

    /**
     * @param int|null $page
     * @param int|null $limit
     * @return ProductPaginatedResource
     */
    public function handle(string $query, ?string $filterBy, ?string $filterValue, ?int $page = 1, ?int $limit = 15): ProductPaginatedResource
    {
        $page = $this->getPage($page);
        $limit = $this->getLimit($limit);
        $offset = $this->getOffset($page, $limit);
        $lastPage = $this->getLastPage($this->repositoryRead->count($query, $filterBy, $filterValue), $limit);

        return new ProductPaginatedResource(
            $this->repositoryRead->index($query, $filterBy, $filterValue, $limit, $offset),
            $lastPage
        );
    }

    public function getFilterableFields(): array
    {
        return ['category_id'];
    }

    public function canFilterBy(string $field): bool
    {
        return in_array($field, $this->getFilterableFields());
    }
}