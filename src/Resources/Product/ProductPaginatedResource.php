<?php

namespace App\Resources\Product;

use App\Entity\Product;
use App\Resources\PaginatedResource;

class ProductPaginatedResource extends PaginatedResource
{
    function formatData(array $data): array
    {
        return array_map(fn($product)=>[
                'id' => $product['id'],
                'title' => $product['title'],
                'description' => $product['description'],
                'category' => $product['name'],
            ],
            $data
        );
    }
}