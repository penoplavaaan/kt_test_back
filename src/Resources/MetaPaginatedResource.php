<?php

namespace App\Resources;

class MetaPaginatedResource
{
    public int $lastPage;

    public function __construct(int $lastPage)
    {
        $this->lastPage = $lastPage;
    }
}