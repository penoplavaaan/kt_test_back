<?php

namespace App\Resources\Statistics;

class ShowStatisticsResource
{
    public int $totalProductCount;
    public int $totalCategoriesCount;
    public float $totalProductsWeightKg;
    public float $avgProductsWeight;
    public array $mostPopularCategories;
    public array $leastPopularCategories;

    public function __construct(
        int $totalProductCount,
        int $totalCategoriesCount,
        float $totalProductsWeightKg,
        float $avgProductsWeight,
        array $mostPopularCategories,
        array $leastPopularCategories
    )
    {
        $this->totalProductCount = $totalProductCount;
        $this->totalCategoriesCount = $totalCategoriesCount;
        $this->totalProductsWeightKg = $totalProductsWeightKg;
        $this->avgProductsWeight = $avgProductsWeight;
        $this->mostPopularCategories = $mostPopularCategories;
        $this->leastPopularCategories = $leastPopularCategories;
    }

    public function toArray(): array
    {
        return [
            ['Общее количество товаров: '.$this->totalProductCount],
            ['Общее количество категорий: '. $this->totalCategoriesCount],
            ['Общая масса товаров, кг: '. $this->totalProductsWeightKg],
            ['Средняя масса товаров: '. $this->totalProductsWeightKg],
            ['Топ-5 категорий: '],
            [$this->mostPopularCategories[0]['name'].' - '.$this->mostPopularCategories[0]['count']],
            [$this->mostPopularCategories[1]['name'].' - '.$this->mostPopularCategories[1]['count']],
            [$this->mostPopularCategories[2]['name'].' - '.$this->mostPopularCategories[2]['count']],
            [$this->mostPopularCategories[3]['name'].' - '.$this->mostPopularCategories[3]['count']],
            [$this->mostPopularCategories[4]['name'].' - '.$this->mostPopularCategories[4]['count']],
        ];
    }
}