<?php

namespace App\Filter;

class ProductFilter implements Filter
{
    private ?string $filterBy;
    private ?string $filterValue;

    public function __construct(?string $filterBy, ?string $filterValue)
    {
        if ($this->canFilterBy($filterBy)){
            $this->filterBy = $filterBy;
            $this->filterValue = $filterValue;
        }else {
            $this->filterBy = null;
            $this->filterValue = null;
        }
    }

    public function getFilterBy(): ?string
    {
        return $this->filterBy;
    }

    public function getFilterValue(): ?string
    {
        return $this->filterValue;
    }

    public function getFilterableFields(): array
    {
        return ['category_id'];
    }

    public function canFilterBy(?string $field): bool
    {
        return in_array($field, $this->getFilterableFields());
    }
}