<?php

namespace App\Entity;

interface CategoryRepositoryPersist
{
    public function save(Category $category): void;
}