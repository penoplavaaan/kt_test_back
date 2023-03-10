<?php

namespace App\Entity;

interface CategoryRepositoryRead
{
    public function list(): array;

    public function getByName(string $name): ?Category;

    public function getById(int $id): ?Category;
}