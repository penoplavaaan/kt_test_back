<?php

namespace App\Entity;

use App\Filter\Filter;
use App\Resources\Statistics\ShowStatisticsResource;

interface ProductRepositoryRead
{
    public function index(string $query, Filter $filter, int $limit, int $offset): array;

    public function count(string $query, Filter $filter): int;

    public function getStatistics(): ShowStatisticsResource;
}