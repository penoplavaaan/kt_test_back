<?php

namespace App\Util;

interface Filterable
{
    public function getFilterableFields(): array;

    public function canFilterBy(string $field): bool;
}