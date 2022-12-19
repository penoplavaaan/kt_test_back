<?php

namespace App\Handler;

use App\Entity\CategoryRepositoryRead;
use App\Entity\Employee;
use App\Entity\ProductRepositoryRead;
use App\Resources\Product\ProductPaginatedResource;
use App\Util\PagerTrait;
use App\Util\Filterable;

final class CategoryList
{
    public function __construct(
        private readonly CategoryRepositoryRead $repositoryRead,
    )
    {
    }

    /**
     * @return array
     */
    public function handle(): array
    {
        return $this->repositoryRead->list();
    }
}