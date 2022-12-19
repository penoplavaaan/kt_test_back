<?php

namespace App\Entity;

interface ProductRepositoryPersist
{
    public function save(Product $product): void;
}