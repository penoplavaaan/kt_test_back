<?php

namespace App\Entity;

interface ProductRepositoryRead
{
    public function list(): array;
    public function index(string $query, string $filterBy, string $filterValue, int $limit, int $offset): array;

    public function count(string $query, ?string $filterBy, ?string $filterValue): int;

    public function getStatistics(): array;
}