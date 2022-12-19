<?php

namespace App\Resources;

abstract class PaginatedResource
{
    public array $data;
    public MetaPaginatedResource $meta;

    public function __construct(array $data, int $lastPage)
    {
        $this->data = $this->formatData($data);
        $this->meta = new MetaPaginatedResource(lastPage: $lastPage);
    }

    abstract function formatData(array $data): array;
}