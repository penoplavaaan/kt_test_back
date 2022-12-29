<?php

namespace App\Handler;

use App\Entity\ProductRepositoryRead;

class ProductsStatistics
{
    public function __construct(
        private readonly ProductRepositoryRead $repositoryRead,
    )
    {
    }

    /**
     * @return array
     */
    public function handle(): array
    {
        return $this->repositoryRead->getStatistics();
    }
}