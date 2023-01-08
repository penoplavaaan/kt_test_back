<?php

namespace App\Filter;

interface Filter
{
    public function getFilterableFields(): array;

    public function canFilterBy(string $field): bool;
}