<?php

namespace App\Handler;

use App\Entity\ProductRepositoryRead;
use App\Resources\Statistics\ShowStatisticsResource;

class ProductsStatistics
{
    public function __construct(
        private readonly ProductRepositoryRead $repositoryRead,
    )
    {
    }

    /**
     * @return ShowStatisticsResource
     */
    public function handle(): ShowStatisticsResource
    {
        return $this->repositoryRead->getStatistics();
    }
}